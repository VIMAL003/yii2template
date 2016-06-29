<?php

namespace common\components;

use Yii;
use yii\base\Component;
use backend\models\Notification;
use backend\models\NotificationQueue;
use backend\models\User;
use backend\models\CompanyLocation;
use backend\models\Client;
use yii\helpers\ArrayHelper;
use yii\db\Query;

class InappComponent extends Component {

    const USER_TYPE = 0;
    const CLIENT_TYPE = 1;

    public function init() {
        
    }

    public function notify($eventCode, $data = array(), $notifiedData = []) {


        switch ($eventCode) {
            case 'groupcompany_update':
            case 'company_update':
            case 'language_update':
                $users = $this->_getActiveProjectManagers();
                break;
            case 'webexlicense_create' :
            case 'webexlicense_update' :
                $users = $this->_getAdminAndProjectManager();
                break;
            case 'brand_update' :
                $users = $this->_getClientProjectManager($data['company_id']);
                break;
            default:
                //echo 'error occur';
                break;
        }


        $inappMessage = Notification::find()->where('event_code = :event_code', [':event_code' => $eventCode])->one();

        if (count($inappMessage) > 0) {
            $message = $inappMessage->message;

            $message = str_replace('__date__', date('d-m-Y'), $message);
            foreach ($data as $field => $value) {
                $message = str_replace('__' . $field . '__', $value, $message);
            }

            // if there are multiple users 
            if (empty($notifiedData)) {
                foreach ($users as $user) {
                    $notificationModel = new NotificationQueue();
                    $notificationModel->person_id = $user['id'];
                    $notificationModel->type = $user['type'];
                    $notificationModel->message = $message;
                    $notificationModel->save(); // Save the notification message for all the recepients
                }
            } else { // if there is specific user
                foreach ($notifiedData as $notificationIdType => $personId) {
                    if (is_array($personId)) {
                        foreach ($personId as $key => $value) {
                            $notificationModel = new NotificationQueue();
                            $notificationModel->person_id = $value;
                            $notificationModel->type = $key;
                            $notificationModel->message = $message;
                            $notificationModel->save();
                        }
                    } else {
                        $notificationModel = new NotificationQueue();
                        $notificationModel->person_id = $personId;
                        $notificationModel->type = $notificationIdType;
                        $notificationModel->message = $message;
                        $notificationModel->save();                   // Save the notification message for the recepients
                    }
                }
            }
        }
    }

    /**
     * 
     * @return type
     */
    protected function _getAdminAndProjectManager() {
        // get all active project managers here    

        $users = User::find()->select(['id', 'type' => new \yii\db\Expression(self::USER_TYPE)])->where(['status' => 1])->asArray()->all();
        return $users;
    }

    /**
     * 
     * @return type
     */
    protected function _getActiveProjectManagers() {
        // get all active project managers here    
        $users = (new Query())->select(['id', 'type' => new \yii\db\Expression(self::USER_TYPE)])->from('user')->where(['user_type' => 2, 'status' => 1])->all();
        return $users;
    }

    /**
     * 
     * @param type $companyId
     * @return type
     */
    private function _getClientProjectManager($companyId = 0) {
        $companyLocation = CompanyLocation::find()->select(['id'])->where(['company_id' => $companyId])->all();
        $mapCompanyLocation = ArrayHelper::getColumn($companyLocation, 'id');

        $clientModel = Client::find()->select(['id', 'type' => new \yii\db\Expression(self::CLIENT_TYPE)])->where(['company_location_id' => $mapCompanyLocation])->asArray()->all();

        $projectManagerModel = Client::find()->select(['user_id AS id', 'type' => new \yii\db\Expression(self::USER_TYPE)])->where(['company_location_id' => $mapCompanyLocation])->andWhere(['not', ['user_id' => null]])->asArray()->all();

        $user = array_merge($clientModel, $projectManagerModel);
       
        return $user;
    }

}

?>