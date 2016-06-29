<?php
namespace backend\components\data;

use yii\db\ActiveQueryInterface;
use yii\data\ActiveDataProvider as DefaultActiveDataProvider;

class ActiveDataProvider extends DefaultActiveDataProvider
{     
    public function setSort($value)
    {          
        parent::setSort($value);
        
        if (($sort = $this->getSort()) !== false && $this->query instanceof ActiveQueryInterface) {
            /* @var $model Model */
            $model = new $this->query->modelClass;
            foreach ($model->attributes() as $attribute) {
                $sort->attributes[$attribute] = [
                    'asc' => [$attribute => SORT_ASC],
                    'desc' => [$attribute => SORT_DESC],
                    'label' => $model->getAttributeLabel($attribute),
                ];
            }
        }
    }
}
?>