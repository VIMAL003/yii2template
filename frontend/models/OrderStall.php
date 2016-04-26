<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order_stall".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $stall_id
 *
 * @property Order $order
 * @property Stall $stall
 */
class OrderStall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_stall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'stall_id'], 'required'],
            [['order_id', 'stall_id'], 'integer'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['stall_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stall::className(), 'targetAttribute' => ['stall_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'stall_id' => Yii::t('app', 'Stall ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStall()
    {
        return $this->hasOne(Stall::className(), ['id' => 'stall_id']);
    }
}
