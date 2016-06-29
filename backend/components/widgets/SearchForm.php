<?php
namespace backend\components\widgets;
use yii\widgets\ActiveForm as DefaultActiveForm;

class SearchForm extends DefaultActiveForm
{    
    public function init()
    {        
        parent::init();        
    }
    
    public static function begin($config = [])
    {
        $defaultOptions = [
                            'action' => ['index'],
                            'method' => 'get',
                            'fieldConfig' => [
                                                'template' => require(__DIR__.'/../../views/templates/search_template.php'),
                                             ],
                          ];
        
        
        if(isset($config['method']) && $config['method'] == 'post')
        {
           unset($defaultOptions['action']);   
        }
        
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