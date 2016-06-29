<?php
namespace backend\components\widgets;
use yii\widgets\DetailView as DefaultDetailView;

class DetailView extends DefaultDetailView
{   
    public function init()
    {        
        parent::init();        
    }
    
    public static function widget($config = [])
    {
        $defaultOptions = [
                            //'options' => ['tag' => 'div', 'class' => 'pull-left col-md-12'],
                            'options' => [],
                            'template' => require(__DIR__.'/../../views/templates/view_template.php'),
                          ];
        
        if(!empty($config))
        {
           $config = array_merge($defaultOptions, $config);
        }
        else
        {
           $config = $defaultOptions;
        }
        
        return parent::widget($config);      
    }
    
}