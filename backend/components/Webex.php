<?php

/**
 * Simple class to integrate with Cisco Webex XML API.
 * Built on version 5.9 of Reference Guide.
 * Based on Webex class by Joshua McGinnis (http://joshuamcginnis.com/2010/07/12/webex-api-php-sdk).
 *
 * @link http://developer.cisco.com/documents/4733862/4736722/xml_api_5+9.pdf
 * @link https://github.com/joubertredrat/phpebex-php-webex
 */

namespace backend\components;

use Yii;
use yii\base\Component;
use backend\models\WebexAdmin;
use backend\models\WebexLicense;
use common\components\helpers\XML2Array;
use common\components\helpers\Array2XML;
use backend\models\Meeting;

class Webex extends Component {

    private $username;
    private $password;
    private $siteID;
    private $partnerID;
    private $url_prefix;
    private $url_host;
    private $send_mode;
    private $data;
    private $action;

    const SEND_CURL = 'curl';
    const SEND_FSOCKS = 'fsocks';
    const PREFIX_HTTP = 'http';
    const PREFIX_HTTPS = 'https';
    const PREFIX_SSL = 'ssl';
    const SUFIX_XML_API = 'WBXService/XMLService';
    const WEBEX_DOMAIN = 'webex.com';
    const XML_VERSION = '1.0';
    const XML_ENCODING = 'UTF-8';
    const USER_AGENT = 'PHPebEx - WebEx PHP API (https://github.com/joubertredrat/phpebex-php-webex)';
    const API_SCHEMA_SERVICE = 'http://www.webex.com/schemas/2002/06/service';
    const API_SCHEMA_MEETING = 'http://www.webex.com/schemas/2002/06/service/meeting';
    const API_SCHEMA_ATTENDEE = 'http://www.webex.com/schemas/2002/06/service/attendee';
    const DATA_SENDER = 'sender';
    const DATA_SENDER_POST_HEADER = 'post_header';
    const DATA_SENDER_POST_BODY = 'post_body';
    const DATA_SENDER_XML = 'xml';
    const DATA_RESPONSE = 'response';
    const DATA_RESPONSE_XML = 'xml';
    const DATA_RESPONSE_DATA = 'data';

    public $response;

    public function test() {
        echo 'You are using Webex common class !!!';
    }

    /**
     * Constructor of class.
     *
     * @return void
     */
    public function __construct() {
        $this->action = 0;
        $this->response = array();
        $this->send_mode = in_array(self::SEND_CURL, get_loaded_extensions()) ? self::SEND_CURL : self::SEND_FSOCKS;
    }

    public function set_credentials() {
        if (empty($this->url_host) || empty($this->url_prefix)) {
            $this->set_url(Yii::$app->params['webex']['xml_api']);
        }

        $this->set_sendmode(Yii::$app->params['webex']['mode']);

        if (empty($this->siteID) && empty($this->partnerID)) {
            // Fetch basic details
            $admin = WebexAdmin::find()->one();
            //->where(['id' => 1])
            //->one();
            if ($admin) {
                $this->siteID = $admin->site_id;
                $this->partnerID = $admin->partner_id;
            }
        }

        // If the license information is not set
        if (empty($this->username) && empty($this->password)) {
            $this->set_license();
        }
    }

    public function set_credentials_2() {
        $this->set_url('ssl://iqkonsumerstrategiez.webex.com');

        $this->siteID = '843227';
        $this->partnerID = '7ls2r69ITVdChM2Ty4U2rg';
        $this->username = 'jayadev';
        $this->password = 'Success@1';
    }

    public function set_license($license=[])
    {       
        // if license details are not provided, then fetch any one randomly
        if(empty($license))
        {
            // Fetch license details
            $license = WebexLicense::find()
                                     ->where('status=:status', [':status' => 1])
                                     ->one();               
            $this->username   = $license->username;
            $this->password   = $license->password;  
        }
        else
        {
            $this->username   = $license['username'];
            $this->password   = $license['password'];            
        }
    }

    public function call_api($api, $params=[])
    {        
        $this->set_credentials();         
        
        if(!empty($this->siteID) && !empty($this->username))
        {
           return $this->$api($params);         
        }
        else
        {
           $result = [];
           $result['status']  = 'error';           
           if(empty($this->siteID))        
           {
              $result['message'] = 'WebEx administrative details have not been configured.';
           } 
           else if(empty($this->username))
           {
              $result['message'] = 'WebEx license details have not been configured.';      
           }            
           $result['exception']  = '00000';    
           
           return $result;
        }
        
    }

    /**
     * Get a possible modes to send a POST data.
     *
     * @return array Returns a list of send modes.
     */
    public static function get_sendmode() {
        return array(self::SEND_CURL, self::SEND_FSOCKS);
    }

    /**
     * Validates a customer webex domain.
     *
     * @param string $url Url to validate.
     * @return bool Return true if a valid url or false if not.
     */
    public static function validate_url($url) {
        $regex = "/^(http|https|ssl):\/\/(([A-Z0-9][A-Z0-9_-]*)+.(" . self::WEBEX_DOMAIN . ")$)/i";
        return (bool) preg_match($regex, $url);
    }

    /**
     * Get port used by API.
     *
     * @param string $prefix Prefix to get a port.
     * @return int Return a port.
     */
    public static function get_port($prefix) {

        $port = '';
        switch ($prefix) {
            case self::PREFIX_HTTP:
                $port = 80;
                break;
            case self::PREFIX_HTTPS:
            case self::PREFIX_SSL:
                $port = 443;
                break;
            default:
                exit(__CLASS__ . ' error report: Wrong prefix');
                break;
        }

        return $port;
    }

    /**
     * Set a url to integrate to webex.
     *
     * @param string $url Customer url.
     * @return void
     */
    public function set_url($url) {
        if (!self::validate_url($url))
            exit(__CLASS__ . ' error report: Wrong webex url');
        list($this->url_prefix, $this->url_host) = preg_split("$://$", $url);
    }

    /**
     * Mode to send data.
     *
     * @param string mode to send.
     * @return void
     */
    public function set_sendmode($mode) {
        if (!in_array($mode, self::get_sendmode()))
            exit(__CLASS__ . ' error report: Wrong send mode');
        $this->send_mode = $mode;
    }

    /**
     * Auth data to integrate with API.
     *
     * @param string $username Username to auth.
     * @param string $password Password to auth.
     * @param string $siteID Customer site id.
     * @param string $partnerID Customer partnerID id.
     * @return void
     */
    public function set_auth($username, $password, $siteID, $partnerID) {
        $this->username = $username;
        $this->password = $password;
        $this->siteID = $siteID;
        $this->partnerID = $partnerID;
    }

    public function set_user($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Generates a XML to send a data to API.
     *
     * @param array $data Data to insert in XML in format:
     * 		$data['service']       = 'meeting';
     * 		$data['xml_header']    = '<item><subitem>data</subitem></item>';
     * 		$data['xml_body']      = '<item><subitem>data</subitem></item>';
     * @return string Returns a XML generated.
     */
    private function get_xml($data) {
        $xml = array();
        $xml[] = '<?xml version="' . self::XML_VERSION . '" encoding="' . self::XML_ENCODING . '"?>';
        $xml[] = '<serv:message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml[] = '<header>';
        $xml[] = '<securityContext>';
        $xml[] = '<webExID>' . $this->username . '</webExID>';
        $xml[] = '<password>' . $this->password . '</password>';
        $xml[] = '<siteID>' . $this->siteID . '</siteID>';
        $xml[] = '<partnerID>' . $this->partnerID . '</partnerID>';
        if (isset($data['xml_header'])) {
            $xml[] = $data['xml_header'];
        }
        $xml[] = '</securityContext>';
        $xml[] = '</header>';
        $xml[] = '<body>';
        $xml[] = '<bodyContent xsi:type="java:com.webex.service.binding.' . $data['service'] . '">';
        $xml[] = $data['payload'];
        $xml[] = '</bodyContent>';
        $xml[] = '</body>';
        $xml[] = '</serv:message>';

        return implode('', $xml);
    }

    /**
     * Test if have a auth data to use a API.
     *
     * @return bool Returns true if have data and false if not.
     */
    public function has_auth() {
        return (bool) $this->username && $this->password && $this->siteID && $this->partnerID;
    }

    /**
     * Generates a header and a body to send data to API.
     *
     * @return string Returns a response from API.
     */
    private function send($xml) {
        $post_data['UID'] = $this->username;
        $post_data['PWD'] = $this->password;
        $post_data['SID'] = $this->siteID;
        $post_data['PID'] = $this->partnerID;
        $post_data['XML'] = $xml;

        // Really I dont know why xml api gives an error on http_build_query :(
        $post_string = '';
        foreach ($post_data as $variable => $value) {
            $post_string .= '' . $variable . '=' . urlencode($value) . '&';
        }
        $post_header = array();
        $post_header[] = 'POST /' . self::SUFIX_XML_API . ' HTTP/1.0';
        $post_header[] = 'Host: ' . $this->url_host;
        $post_header[] = 'User-Agent: ' . self::USER_AGENT;
        if ($this->send_mode == self::SEND_FSOCKS) {
            $post_header[] = 'Content-Type: application/xml';
            $post_header[] = 'Content-Length: ' . strlen($xml);
        }
        $data = array();
        $data['post_header'] = $post_header;
        $data['post_string'] = $post_string;
        $data['post_xml'] = $xml; // RSPL - AJG
        $this->data[$this->action][self::DATA_SENDER][self::DATA_SENDER_POST_HEADER] = $post_header;
        $this->data[$this->action][self::DATA_SENDER][self::DATA_SENDER_POST_BODY] = $post_header;
        $this->data[$this->action][self::DATA_SENDER][self::DATA_SENDER_XML] = $xml;
        return $this->{'send_' . $this->send_mode}($data);
    }

    /**
     * Send a data to Webex API using PHP curl.
     *
     * @param array $data Data to send to API in format:
     * 		$data['post_header'] = "blablabla";
     * 		$data['post_header'] = "post_string";
     * @return string Returns a response from API.
     */
//    private function send_curl($data) {
//            extract($data);
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $this->url_prefix . '://' . $this->url_host . '/' . self::SUFIX_XML_API);
//            curl_setopt($ch, CURLOPT_PORT, self::get_port($this->url_prefix));
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $data['post_header']);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data['post_string']);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $responseStr = curl_exec($ch);
//            if($responseStr === false)
//                    exit(__CLASS__ . ' error report: Curl error - ' . curl_error($ch));
//            curl_close($ch);
//            return $responseStr;
//    }
    /**
     * Send a data to Webex API using PHP file functions.
     *
     * @param array $data Data to send to API in format:
     * 		$data['post_header'] = "blablabla";
     * 		$data['post_header'] = "post_string";
     * @return string Returns a response from API.
     * @todo haha, I need to test this :)
     */
    private function send_fsocks($data) {
        extract($data);

        $post_data = implode("\n", $data['post_header']) . "\n\n" . $data['post_xml'] . "\n";

        $fp = fsockopen($this->url_prefix . '://' . $this->url_host, self::get_port($this->url_prefix), $errno, $error);
        if ($fp) {
            fwrite($fp, $post_data);
            $responseStr = '';
            while (!feof($fp)) {
                $responseStr .= fgets($fp, 1024);
            }
            return $responseStr;
        } else
            exit(__CLASS__ . ' error report: Fsocks error - (' . $errno . ') ' . $error);
    }

    /**
     * Get response from a API.
     *
     * @param string $type Type of data to be requested.
     * @param int $number number of sender to be requested.
     * @return string|object Return a response.
     */
    public function get_response($type = self::DATA_RESPONSE_DATA, $number = null) {
        if (isset($number) && is_int($number)) {
            if ($number < 1 || $number > ($this->action - 1))
                exit(__CLASS__ . ' error report: Invalid response number');
            $number--;
        } else
            $number = ($this->action - 1);
        //var_dump($number);

        $responseStr = '';
        switch ($type) {
            case self::DATA_RESPONSE_XML:
            case self::DATA_RESPONSE_DATA:
                $responseStr = $this->data[$number][self::DATA_RESPONSE][$type];
                break;
            default:
                exit(__CLASS__ . ' error report: I don\'t undestood that data you needs');
                break;
        }

        return $responseStr;
    }

    /**
     * Transmit the payload data to the XML APIs
     * @author Ajay Giri
     * @param type $function
     * @param type $payload
     * @return type
     */
    public function transmit($function, $payload) {
        if (!$this->has_auth()) {
            exit(__CLASS__ . ' error report: Authentication data not found');
        }

        $data = array();
        $data['payload'] = trim(implode('', $payload));
        $data['service'] = str_replace("_", ".", $function);

        $xml = $this->get_xml($data);
        $responseStr = $this->send($xml);

        $responseStr = strstr($responseStr, '<?xml');
        return $responseStr;
    }

    /**
     * Process the response received from the XML APIs
     * @author Ajay Giri
     * @param type $xmlReponse
     * @param type $schema
     */
    public function processResponseData($xmlReponse, $schema, $list = false) {
        $responseStr = XML2Array::createArray($xmlReponse);
        $responseStr = $responseStr['serv:message']; // parent element

        $result = array();
        if (strtoupper($responseStr['header']['response']['result']) == 'SUCCESS') {
            $result['status'] = 'success';
            $result['data'] = $responseStr['body']['bodyContent'];
        } else {
            $result['status'] = 'error';
            $result['exception'] = $responseStr['header']['response']['exceptionID'];
            $result['message'] = $responseStr['header']['response']['reason'];
        }

        return $result;
    }

    /**
     * @author Ajay Giri
     * @param type $maximumNum
     */
    public function meeting_LstsummaryMeeting($params = []) {

        $maximumNum = 10;
        if (!empty($params['maximumNum'])) {
            $maximumNum = $params['maximumNum'];
        }

        // Create payload data
        $payload = array();
        $payload[] = '<listControl>';
        $payload[] = '<startFrom/>';
        $payload[] = '<maximumNum>' . $maximumNum . '</maximumNum>';
        $payload[] = '</listControl>';
        $payload[] = '<order>';
        $payload[] = '<orderBy>STARTTIME</orderBy>';
        $payload[] = '<orderAD>DESC</orderAD>';
        $payload[] = '</order>';
        $payload[] = '<dateScope>';
        $payload[] = '</dateScope>';

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);

        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING, true);
        return $result;
    }

    private function createXML($data = []) {
        // Convert array to XML string
        $xml = Array2XML::createXML('parentNode', $data);
        $xml = $xml->saveXML();

        // Remove the parent node from the resultant XML string and the XML header too
        $xml = Array2XML::removeParentNode($xml, 'parentNode');

        return $xml;
    }

    /**
     * Schedule new WebEx meeting
     * @author Ajay Giri
     * @param type $params
     * @return mixed
     */
    public function meeting_CreateMeeting($params = []) {

        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);

        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING);
        return $result;
    }

    /**
     * Update existing WebEx meeting
     * @author Ajay Giri
     * @param type $params
     * @return mixed
     */
    public function meeting_SetMeeting($params = []) {

        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);

        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING);
        return $result;
    }

    /**
     * @author Ajay Giri
     * @param type $params
     * @return mixed
     */
    public function meeting_GetMeeting($params = []) {

        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);

        // Strange: I don't know why this has to be written, else this child objects wont be considered in the final output
        $this->response = str_replace('<person:', '<att:', $this->response);
        $this->response = str_replace('</person:', '</att:', $this->response);

        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING);
        return $result;
    }

    /**
     * @author Ajay Giri
     * @param type $params
     * @return mixed
     */
    public function meeting_DelMeeting($params = []) {

        // Create payload data
        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);
        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING);
        return $result;
    }

    /**
     * @author Ajay Giri
     * @param type $params
     * @return mixed
     */
    public function user_GetUser($params = []) {

        // Create payload data
        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);
        $result = $this->processResponseData($this->response, self::API_SCHEMA_SERVICE);
        return $result;
    }

    public function validLicense($licenceDetails = []) {
        if (isset($licenceDetails['username']) && isset($licenceDetails['password'])) {
            $this->set_license($licenceDetails);

            $meetingDetails = [
                'webExId' => $licenceDetails['username'],
            ];

            $this->response = $this->call_api('user_GetUser', $meetingDetails);

            if (isset($this->response['status'])) {
                if ($this->response['status'] == 'success') {
                    return 1;  // Verification passed      
                } else {
                    return 0;  // Verification failed    
                }
            } else {
                return -1;  // WebEx server not available  
            }
        }

        return 0; // Verification failed  
    }

    /**
     * @author Ajay Giri
     * @param type $mKey
     * @return mixed
     */
    public function meeting_GethosturlMeeting($params = []) {

        $mKey = '';
        if (!empty($params['sessionKey'])) {
            $mKey = $params['sessionKey'];
        }

        // Create payload data
        $payload = array();
        $payload[] = '<sessionKey>' . $mKey . '</sessionKey>';

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);
        $result = $this->processResponseData($this->response, self::API_SCHEMA_MEETING);
        //pr($result); die();
        return $result;
    }

    public function attendee_CreateMeetingAttendee() {
        // Create payload data
        $payload = array();
        $payload[] = <<<XML

        <person>
           <name>Pratik Patel</name>
           <address>
             <addressType>PERSONAL</addressType>
           </address>
           <email>pratik.patel@rishabhsoft.com</email>
           <type>VISITOR</type>
        </person>
        <role>ATTENDEE</role>
        <sessionKey>627358673</sessionKey> 
        <emailInvitations>TRUE</emailInvitations>

XML;

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);
        // print_r($response;)
        // Strange: I don't know why this has to be written, else this child objects wont be considered in the final output
        $this->response = str_replace('<com:', '<att:', $this->response);
        $this->response = str_replace('</com:', '</att:', $this->response);
        // End - strange

        $this->processResponseData($this->response, self::API_SCHEMA_ATTENDEE);
    }

    //public function attendee_LstMeetingAttendee()
    public function attendee_LstMeetingAttendee($params = []) {

        $sessionKey = '';
        if (!empty($params['sessionKey'])) {
            $sessionKey = $params['sessionKey'];
        }

        // Create payload data
        $payload = array();
        $payload[] = '<sessionKey>' . $sessionKey . '</sessionKey>';

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);

        // Strange: I don't know why this has to be written, else this child objects wont be considered in the final output
        $this->response = str_replace('<com:', '<att:', $this->response);
        $this->response = str_replace('</com:', '</att:', $this->response);
        // End - strange

        $this->processResponseData($this->response, self::API_SCHEMA_ATTENDEE);
    }

    //public function user_AuthenticateUser()
    //public function user_CreateUser()
    //public function user_DelUser()
    //public function user_DelSessionTemplates()
    //public function user_GetloginTicket()
    //public function user_GetloginurlUser()
    //public function user_GetlogouturlUser()
    //public function user_GetUser()
    //public function user_LstsummaryUser()
    //public function user_SetUser()
    //public function user_UploadPMRIImage()
    //public function meeting_CreateMeeting()
    //public function meeting_CreateTeleconferenceSession()
    //public function meeting_DelMeeting()
    //public function meeting_GethosturlMeeting()
    //public function meeting_GetMeeting()
    //public function meeting_GetTeleconferenceSession()
    //public function meeting_LstsummaryMeeting()
    //public function meeting_SetMeeting()
    //public function meeting_SetTeleconferenceSession()
    //public function meeting_GetjoinurlMeeting()
    //public function event_CreateEvent()
    //public function event_DelEvent()
    //public function event_GetEvent()
    //public function event_LstRecordedEvent()
    //public function event_LstsummaryProgram()
    //public function event_SendInvitationEmail()
    //public function event_SetEvent()
    //public function event_UploadEventImage()
    //public function event_LstsummaryEvent()
    //public function attendee_CreateMeetingAttendee()
    //public function attendee_LstMeetingAttendee()
    //public function attendee_RegisterMeetingAttendee()
    //public function history_LstmeetingattendeeHistory()   
    // NBR API
    public function ep_LstRecording($params = []) {

        // Create payload data
        $payload = array();
        $payload[] = $this->createXML($params);

        // Calling the web servcie
        $this->response = $this->transmit(__FUNCTION__, $payload);
        $result = $this->processResponseData($this->response, self::API_SCHEMA_SERVICE);
        return $result;
    }

    public function meetingRecordings($mKey = '') {
        $mDetails = [
            'listControl' => [
                'startFrom' => 0,
                'maximumNum' => 10,
            ],
            'sessionKey' => $mKey,
        ];

         $this->response = $this->call_api('ep_LstRecording', $mDetails);
         //pr($this->response);
         
         if($this->response['status'] == 'success')
         {
             $result['status'] = 'success';
             
             if(isset($this->response['data']['recording'][0]))
             {
                //$list = $this->response['data']['recording'][0];   
                $list = $this->response['data']['recording']; 
             }
             else
             {
                $list[0] = $this->response['data']['recording'];
             }
             
             $result['recording'] = $list;
         }
         else
         {
             $result['status'] = 'error'; 
             $result['exception'] = $this->response['exception'];
             $result['message'] = $this->response['message'];    
         }
                
         return $result;
    
    }
    /**
     * 
     * @param type $mKey
     * @return type
     */
    public function meetingStatus($mKey = '') {
        $mDetails = [
            'meetingKey' => $mKey,
        ];
        $this->response = $this->call_api('meeting_GetMeeting', $mDetails);

        if ($this->response['status'] == 'success') {
            $result['status'] = 'success';
            $result['meetingStatus'] = $this->response['data']['status'];
        } else {
            $result['status'] = 'error';
            $result['message'] = $this->response['message'];
        }

        return $result;
    }

}
