<?php

namespace common\components\helpers;

use Yii;
use yii\helpers;
use yii\helpers\Url;
use backend\components\helpers\Html;
use DateTime;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

class Util {

    /** Get the status in value
     * 
     * @param type $value
     * @param type $options
     * @return string
     */
    public static $projectPaymentType = [
        1 => 'Cash',
        2 => 'Cheque',
        3 => 'Demand draft',
        4 => 'Bank credit',
        5 => 'Other'];
    public static $expertiseProfileType = [
        0 => 'Basic',
        1 => 'Advanced'];

    public static function statusView($value, $options = array()) {
        $type = 'status';
        if (!empty($options['type'])) {
            $type = $options['type'];
        }

        if ($type == 'status') {
            $status = array('0' => Yii::t('app', 'Archived'), '1' => Yii::t('app', 'Active'));
            $cssClass = array('0' => 'danger', '1' => 'success');
        } else if ($type == 'project_status') {
            $status = array('0' => Yii::t('app', 'Not yet created'), '1' => Yii::t('app', 'In progress'), '2' => Yii::t('app', 'Completed'), '3' => Yii::t('app', 'Cancelled'));
            $cssClass = array('0' => 'default', '1' => 'success', '2' => 'primary', '3' => 'danger');
        } else if ($type == 'meeting_status') {
            $status = array('0' => Yii::t('app', 'Not in progress'), '1' => Yii::t('app', 'In progress'), '2' => Yii::t('app', 'Completed'), '3' => Yii::t('app', 'Cancelled'));
            $cssClass = array('0' => 'default', '1' => 'success', '2' => 'primary', '3' => 'danger');
        }
        
        $markup = '<span class="label label-' . $cssClass[$value] . '" style="cursor:auto;float:auto">' . $status[$value] . '</span> ';
        return $markup;
    }

    /** get text value based upon type
     * 
     * @param type $value
     * @param type $options
     */
    public static function getTextFromValue($value, $options = array()) {

        $type = 'boolean';
        if (!empty($options['type'])) {
            $type = $options['type'];
        }

        if ($type == 'client_type') {
            $finalValue = $value == 1 ? Yii::t('app', 'Contracted') : Yii::t('app', 'Adhoc');
        } else if ($type == 'boolean') {
            $finalValue = $value == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
        return $finalValue;
    }

    public static function paymentTypeList($options = array()) {
        return [
                1 => Yii::t('app', 'Cash'),
                2 => Yii::t('app', 'Cheque'),
                3 => Yii::t('app', 'Demand draft'),
                4 => Yii::t('app', 'Bank credit'),
                5 => Yii::t('app', 'Other')
            ];
    }

    public static function paymentType($value, $options = array()) {

        $paymentTypeList = static::paymentTypeList();
        if (empty($value)) {
            $value = 5;
        }
        return $paymentTypeList[$value];
    }

    public static function expertiseProfileTypeList($options = array()) {
        return [
            0 => 'Basic',
            1 => 'Advanced'];
    }

    public static function expertiseProfileType($value, $options = array()) {

        $expertiseProfileTypeList = static::expertiseProfileTypeList();
        if (empty($value)) {
            $value = 0;
        }
        return $expertiseProfileTypeList[$value];
    }

    /** get short text from it's value
     * 
     * @param type $textValue
     * @param type $maxLength
     * @return type
     */
    public static function getTruncatedText($textValue, $maxLength, $showDots = true) {

        if ($showDots) {
            $truncatedString = (strlen($textValue) > $maxLength) ? substr($textValue, 0, $maxLength) . '...' : $textValue;
        } else {
            $truncatedString = (strlen($textValue) > $maxLength) ? substr($textValue, 0, $maxLength) : $textValue;
        }
        return Html::encode($truncatedString);
    }

    public static function getActionLink($button = '', $url = [], $options = []) {

        // Check access permission - for admin section only
        if (isset(Yii::$app->user->identity->user_type)) {
            $userType = (Yii::$app->user->identity->user_type == 1) ? 'admin' : 'pm';

            $access = Yii::$app->params['access'];

            $controller = $url['controller'];
            $action = $url['action'];

            // If action not allowed
            if (!in_array($action, $access[$controller][$userType])) {
                return '';
            }
        }

        $url[0] = $url['controller'] . '/' . $url['action'] . '/';
        unset($url['controller']);
        unset($url['action']);

        //$url = urldecode(Url::toRoute(['brief/create/', 'clientId' => $model->id]));
        $url = urldecode(Url::toRoute($url));
        $url = str_replace(' ', '+', $url);

        switch ($button) {
            case 'update';
                $iconClass = 'glyphicon-pencil';
                break;

            case 'markadhoc';
            case 'clientRating';
                $iconClass = 'glyphicon-star';
                break;

            case 'addbrief';
            case 'acknowledge';
            case 'editparticipant':
                $iconClass = 'glyphicon-user';
                break;

            case 'associateCompany';
            case 'emailPanelContact';
                $iconClass = 'glyphicon-globe';

                break;

            case 'companyLocations';
            case 'companyLocationTeamMember';
                $iconClass = 'glyphicon-tree-conifer';
                break;

            case 'associateclient';
            case 'emailModerator';
                $iconClass = 'glyphicon-resize-horizontal';
                break;

            case 'translation';
            case 'discussionGuide';
                $iconClass = 'glyphicon-subtitles';
                break;

            case 'view';
                $iconClass = 'glyphicon-eye-open';
                break;

            case 'viewRating';
            case 'setRate':
                $iconClass = 'glyphicon-star';
                $options['source'] = $url;
                $url = '#';
                break;

            case 'projectPayment';
            case 'meetingRating';
                $iconClass = 'glyphicon-usd';
                break;

            case 'delete';
                $iconClass = 'glyphicon-remove';
                break;

            case 'updateM':
                $iconClass = 'glyphicon-pencil';
                $options['source'] = $url;
                $url = '#';
                break;

            case 'cancel':
                $iconClass = 'glyphicon-remove-circle';
                break;

            case 'complete':
                $iconClass = 'glyphicon-ok-circle';
                break;

            case 'addproposal':
            case 'createproject':
                $iconClass = 'glyphicon-plus';
                break;

            case 'approveproposal':
                $iconClass = 'glyphicon-ok';
                break;

            default:
                //echo 'error occur';
                break;
        }

        //$iconClass = '<span class="glyphicon ' . $iconClass . '">' . $options['title'] . '</span>';
        $iconClass = '<div>' . $options['title'] . '</div>'; // temp code

        return Html::a($iconClass, $url, $options);
    }

    public static function projectStatus($value, $options = array()) {

        switch ($value) {
            case '1':
                $status = Yii::t('app', 'In Progress');
                break;

            case '2':
                $status = Yii::t('app', 'Completed');
                break;

            case '3':
                $status = Yii::t('app', 'Cancelled');
                break;

            default:
                //echo 'error occur';
                break;
        }

        return $status;
    }

    public static function meetingStatus($value, $options = array()) {

        switch ($value) {
            case '0':
                $status = Yii::t('app', 'Not in progress');
                break;

            case '1':
                $status = Yii::t('app', 'In progress');
                break;

            case '2':
                $status = Yii::t('app', 'Completed');
                break;

            case '3':
                $status = Yii::t('app', 'Cancelled');
                break;
            default:
                //echo 'error occur';
                break;
        }

        return $status;
    }

    public static function notificationToList($options = array()) {
        return [
                1 => Yii::t('backend', 'Administrator'), 
                2 => Yii::t('backend', 'Project Manager'), 
                3 => Yii::t('backend', 'Client'), 
                4 => Yii::t('backend', 'Project Manager + Client'), 
                5 => Yii::t('backend', 'Administrator + Project Manager')
               ];
    }

    public static function notificationToValue($value, $options = array()) {

        $notificationToList = static::notificationToList();
        if (empty($value)) {
            return '';
        }
        return $notificationToList[$value];
    }

    public static function dateString($mysqlDate, $format = '') {
        if (empty($format))
            $date = date('jS F, Y', strtotime($mysqlDate));
        else
            $date = date($format, strtotime($mysqlDate));

        return $date;
    }

    public static function dateString2($mysqlDate, $format = '') {

        if (empty($mysqlDate))
            return Yii::t('backend', 'N/A');

        if (empty($format))
            $date = date('j', strtotime($mysqlDate)) . '<sup>' . date('S', strtotime($mysqlDate)) . '</sup> ' . date('M, Y', strtotime($mysqlDate));
        else
            $date = date($format, strtotime($mysqlDate));

        return $date;
    }

    public static function timeString($mysqlTime, $format = '') {
        if (empty($format))
            $time = date('h:i:s A', strtotime($mysqlTime));
        else
            $time = date($format, strtotime($mysqlTime));

        return $time;
    }

    /**
     *  This function is used encodes the string
     *  @author Zaver M. Vaghasiya
     *  @param string $string - A string to be encrypted
     *  @param string $key - A 'key' or 'password' that is used to decode the string later.
     *  @return string - Returns the encrypted version of the provided string
     */
    public static function Encrypt($string, $key = 'CCP') {
        $result = '';

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }

    /**
     *  This function is used to decodes a string
     *  @param string $string - A string to be decrypt
     *  @param string $key - The key used to 'unlock' the data
     *  @return string - The original string if the key is correct, and gibberish otherwise.
     */
    public static function Decrypt($string, $key = 'CCP') {
        $result = '';

        $string = base64_decode($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }

        return $result;
    }

    public static function currencyCode($curencyId = '') {
        $currencyCodes = ['1' => '€', '2' => '£', '3' => '$', '4' => '₹'];

        return $currencyCodes[$curencyId];
    }

    /**
     * 
     * @param type $datetime
     * @param type $full
     * @return type
     */
    public static function timeElapsedString($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hr',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function modalPopup($title, $options = null) {

        $attributes = ['header' => $title,
            'id' => 'cppmodal',
            'size' => 'modal-lg'
        ];

        if (isset($options)) {
            $attributes = array_merge($attributes, $options);
        }

        Modal::begin($attributes);

        echo "<div id='modalContent'></div>";
        Modal::end();
    }

    /**
     * 
     * @param type $array
     * @param type $subkey
     * @param type $sortType
     * @return type
     */
    public static function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {
        foreach ($array as $subarray) {
            $keys[] = $subarray[$subkey];
        }
        array_multisort($keys, $sortType, $array);
        return $array;
    }
    
    public static function roundToHalf($number=0)
    {
        $number = round($number, 1);
        $arr = explode('.', $number);  
        
        if(isset($arr[1]))
        {
            $add = ($arr[1]>5)?1:(($arr[1]<5)?0:0.5);                        
            $number = (floor($number)) + $add;
        }
        
        return $number;
    }
    
    public static function getDisabledOptions($activeOptions=[], $modelOptions=[], $options=[])
    {
        if(empty($options)){
            $modelOptions = ArrayHelper::map($modelOptions, 'id', 'name');
        }
        $diff = array_diff_key($modelOptions, $activeOptions);
       
        return $diff;
    }
    
    public static function formatSelect2Disabled($disabledOptions=[])
    {
        $format = [];
        foreach($disabledOptions as $key => $value)
        {
            $format[$key]['disabled'] = true;
        }
        
        return $format;
    }
    
    public static function limitCharacters($elementId = '', $maxLength = 500)
    {
        if($maxLength > 0 && !empty($elementId))
        {           
            Yii::$app->view->registerCssFile('/css/jquery.maxlength.css');
            Yii::$app->view->registerJsFile('/js/jquery.plugin.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
            Yii::$app->view->registerJsFile('/js/jquery.maxlength.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
            
            $js = <<<JS
            $(function() {
                    $('#$elementId').maxlength({max:$maxLength});                   
            });
JS;
 
            Yii::$app->view->registerJs($js,\yii\web\View::POS_END); 

            return true;
        }
        
        return false;
    }

}
