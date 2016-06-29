<?php

namespace common\components;

use Yii;
use yii\base\Component;
use backend\models\Email;

class EmailComponent extends Component {

    /**
     * 
     * @param type $eventCode
     * @param type $data
     * @return boolean
     */
    public function sendInstant($eventCode, $data = array(), $options = array(), $groupEmail = []) {

        // Fetch the email details
        $emailMessage = Email::find()->where('event_code = :event_code', [':event_code' => $eventCode])->one();

        if (count($emailMessage)) {
            $subject = $emailMessage->subject;
            $message = $emailMessage->message;

            foreach ($data as $field => $value) {
                $message = str_replace('__' . $field . '__', $value, $message);
                $subject = str_replace('__' . $field . '__', $value, $subject);
            }

            // Send email to user
            if (!empty($data['email'])) {
                $this->_sendEmail($data['email'], $subject, $message, $options, $groupEmail);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 
     * @param string $email
     * @param type $subject
     * @param type $message
     * @return type
     */
    public function _sendEmail($email, $subject, $message, $options = array(), $groupEmail = []) {

        $adminEmail = Yii::$app->params['adminEmail'];
        
        if (!empty($groupEmail)) {  
            $groupEmail = 'vimal.patel@rishabhsoft.com';
            $emailSend = Yii::$app->mailer->compose(['html' =>'@common/mail/email/html'],['content' => $message])
                    ->setFrom($adminEmail)
                    ->setTo($groupEmail)
                    ->setSubject($subject);
        } else { 
            $email = 'vimal.patel@rishabhsoft.com';
            $emailSend = Yii::$app->mailer->compose(['html' =>'@common/mail/email/html'],['content' => $message])
                    ->setFrom($adminEmail)
                    ->setTo($email)
                    ->setSubject($subject); //->setHtmlBody($message);
        }
        if (isset($options['has_attachment']) && !empty($options['has_attachment'])) {
            $emailSend->attach($options['filePath']);
            //$emailSend->attachContent('<div style="color:red">Attachment content</div>', ['fileName' => 'attach.pdf', 'contentType' => 'application/pdf']);
        }
        return $emailSend->send();
    }

}

?>