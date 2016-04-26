<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $mobile
 * @property string $date
 *
 * @property OrderStall[] $orderStalls
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $confirm_mobile;
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'mobile'], 'required'],
            [['address'], 'string', 'max' => 250],
            [['mobile'], 'integer'],
            ['mobile','string', 'max'=>10],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 40],
            ['confirm_mobile', 'compare', 'compareAttribute' => 'mobile', 'message' => Yii::t('app', 'The mobile and confirm mobile does not match.'), 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'mobile' => Yii::t('app', 'Mobile'),
            'date' => Yii::t('app', 'Date'),
            'confirm_mobile' => Yii::t('app', 'confirm_mobile'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStalls()
    {
        return $this->hasMany(OrderStall::className(), ['order_id' => 'id']);
    }
}
