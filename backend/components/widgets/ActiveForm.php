<?php
namespace backend\components\widgets;
//use yii\widgets\ActiveForm as DefaultActiveForm;
use yii\bootstrap\ActiveForm as DefaultActiveForm;

class ActiveForm extends DefaultActiveForm
{    
    public function init()
    {        
        parent::init();        
    }
    
    public static function begin($config = [])
    {
        $defaultOptions = [
                            'validateOnBlur' => FALSE,
                            'fieldConfig' => [
                                                'template' => require(__DIR__.'/../../views/templates/input_template.php'),
                                                'labelOptions' => ['class' => 'col-md-5'],
                                             ],
                          ];
        
        if(!empty($config))
        {
           $config = array_merge($defaultOptions, $config);
        }
        else
        {
           $config = $defaultOptions;
        }
        
        return parent::begin($config);      
    }
    
}