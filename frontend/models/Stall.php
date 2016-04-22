<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "stall".
 *
 * @property integer $id
 * @property integer $area_id
 * @property integer $name
 *
 * @property CityArea $area
 */
class Stall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'name'], 'required'],
            [['area_id', 'name'], 'integer'],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => CityArea::className(), 'targetAttribute' => ['area_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'area_id' => Yii::t('app', 'Area ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(CityArea::className(), ['id' => 'area_id']);
    }
}
