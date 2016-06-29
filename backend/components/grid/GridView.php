<?php
namespace backend\components\grid;

use Yii;
use yii\grid\GridView as DefaultGridView;
use yii\widgets\LinkPager;

class GridView extends DefaultGridView
{   
    public function init()
    {        
        parent::init(); 
    }
    
    public static function widget($config = [])
    {
        $defaultOptions = [     
                            'tableOptions' =>['class' => 'table table-striped table-bordered footable'],
                            'layout' => require(__DIR__.'/../../views/templates/list_template.php'),
                            'pager' =>  [
                                            'firstPageLabel' => 'First',
                                            'lastPageLabel' => 'Last',
                                            'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
                                            'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
                                            'maxButtonCount' => '5',
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
        
        // Apply bootstrap footable class to GridView table
        static::applyBootstrapFootable($config);        
        
        // Applying dropdown for action column on the gridview
        //pr($config['columns']);        
        foreach ($config['columns'] as $key => $value)
        {            
            if(isset($value['class']) && $value['class'] == 'yii\grid\ActionColumn')
            {               
                $config['columns'][$key]['class'] = \microinginer\dropDownActionColumn\DropDownActionColumn::className();
                $config['columns'][$key]['headerOptions'] = ['width' => '100'];
                $config['columns'][$key]['header'] = '';
                break;
            }
        } 
        // End of code
        
        return parent::widget($config);      
    }
    
    public static function applyBootstrapFootable(&$config)
    {
        // Applying footable class to table data
        $controller = Yii::$app->controller->id;
        
        if(!isset(Yii::$app->params['footable'][$controller]))
        {
           return;
        }
        
        $footable = Yii::$app->params['footable'][$controller];
        
        $firstColumn = true;        
        foreach ($config['columns'] as $key => $value)
        {
            if(isset($value['class']) && $value['class'] == 'yii\grid\ActionColumn')
            {
                continue;
            }
            
            $devices = '';
            if(is_array($value) && isset($value['attribute']))
            {                               
               if(!in_array($value['attribute'], $footable['phone']))
               {
                  $devices .=  'phone,';  
               }
               
               if(!in_array($value['attribute'], $footable['ipad']))
               {
                  $devices .=  'tablet,';  
               }
               
               if(!empty($devices))
               {
                  $devices= substr($devices,0,-1);
                  $config['columns'][$key]['headerOptions'] = ['data-hide' => $devices];                 
               }
               
               if($firstColumn)
               {
                  $config['columns'][$key]['contentOptions'] = ['class' => 'expand'];
                  $config['columns'][$key]['contentOptions'] = ['class' => 'expand'];
                  $firstColumn = false;
               }
               
            }
            else
            {                
               if(!in_array($value, $footable['phone']))
               {
                  $devices .=  'phone,';  
               }
               
               if(!in_array($value, $footable['ipad']))
               {
                  $devices .=  'tablet,';  
               }
               
               if(!empty($devices))
               {
                  $devices= substr($devices,0,-1);
                  $config['columns'][$key] = [
                      'attribute' => $value,
                      'headerOptions' => ['data-hide' => $devices]                 
                  ];
               }
                             
               if($firstColumn)
               {
                  $config['columns'][$key] = [
                      'attribute' => $value,
                      'headerOptions' => ['class' => 'expand'],
                      'contentOptions' => ['class' => 'expand']
                  ];                  
                  $firstColumn = false;
               }
            }
            
            //pr($config['columns'][$key]);
        }
        reset($config);
        // End of code
    }
    
}