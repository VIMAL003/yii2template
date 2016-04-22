<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
/**
 * ContactForm is the model behind the contact form.
 */
class SearchForm extends Model
{

    public $city;
    public $area;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['city', 'required','message'=>Yii::t('app', 'Please select your city.')],
            ['area', 'required','message'=>Yii::t('app', 'Please select your area.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'verifyCode' => Yii::t('frontend', 'Verification Code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * -- not in use --
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($userEmail)
    {
        return Yii::$app->mailer->compose()
                ->setTo($userEmail)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
    }
    
    public static function getCityList() {
        $models = City::find()->orderBy('name')->all();
        return ArrayHelper::map($models, 'id', 'name');
    }
}