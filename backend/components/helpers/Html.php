<?php

namespace backend\components\helpers;

use yii\helpers\Html as DefaultHtml;
use Yii;
class Html extends DefaultHtml {

    /**
     * Generates a reset button tag.
     * @param string $content the content enclosed within the button tag. It will NOT be HTML-encoded.
     * Therefore you can pass in HTML code such as an image tag. If this is is coming from end users,
     * you should consider [[encode()]] it to prevent XSS attacks.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     * @return string the generated reset button tag
     */
    public static function resetButton($content = 'Reset', $options = []) {
        $options['class'] = 'btns pull-right bluegrad';
        
        $content = "<span class='glyphicon glyphicon-remove'></span>&nbsp;&nbsp;" . $content;
        return parent::resetButton($content, $options);
        //return parent::button($content, $options);
    }

    /**
     * Generates a submit button tag.
     *
     * Be careful when naming form elements such as submit buttons. According to the [jQuery documentation](https://api.jquery.com/submit/) there
     * are some reserved names that can cause conflicts, e.g. `submit`, `length`, or `method`.
     *
     * @param string $content the content enclosed within the button tag. It will NOT be HTML-encoded.
     * Therefore you can pass in HTML code such as an image tag. If this is is coming from end users,
     * you should consider [[encode()]] it to prevent XSS attacks.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     * @return string the generated submit button tag
     */
    public static function submitButton($content = 'Submit', $options = []) {
        
        
        if(isset($options['class']))
        {
           $options['class'] = $options['class'] . ' btns pull-right greengrad';
        }
        else
        {
           $options['class'] = 'btns pull-right greengrad';   
        }
        
        if(isset($options['act']) && $options['act'] != "")
        {            
            if($options['act'] == 'update')
            {
                $optionMessage = 'Are you sure you want to update?';
                if(isset($options['message']))
                {
                   $optionMessage = $options['message'];
                }
                
                if(isset($options['switchMessage']) && $options['switchMessage'] == 1)
                {
                   $js = <<<JS
                    // get the form id and set the event                      
                    $('form:last').on('beforeSubmit', function(e) {
                        var \$form = $(this);              
                        if(yii.confirm(confirmMsg, 'submitThisForm'))
                        {     
                           return true;
                        }
                        else
                        {      
                           return false;
                        }
                       // do whatever here, see the parameter \$form? 
                       // is a jQuery Element to your form
                    }).on('submit', function(e){
                        $(':input').attr('disabled', false);
                        //e.preventDefault();
                    });           
JS;
   
                }
                else
                {
                    $js = <<<JS
                    // get the form id and set the event        
                    $('form:last').on('beforeSubmit', function(e) {
                        var \$form = $(this);              
                        if(yii.confirm('$optionMessage', 'submitThisForm'))
                        {     
                           return true;
                        }
                        else
                        {      
                           return false;
                        }
                       // do whatever here, see the parameter \$form? 
                       // is a jQuery Element to your form
                    }).on('submit', function(e){
                        $(':input').attr('disabled', false);
                        //e.preventDefault();
                    });                
JS;
                }                        

                \yii::$app->view->registerJs($js);
                //unset($options['act']);
            }
            else if($options['act'] == 'search')
            {
                $js = <<<JS
                    // get the form id and set the event        
                    $('#formsearch').on('submit', function(e){                        
                        $('#globalsearch').val($.trim($('#globalsearch').val()));
                        //e.preventDefault();
                    });                
JS;
                
                \yii::$app->view->registerJs($js);
            }
            
        }
        else
        {
            \yii::$app->view->registerJs(" $('form:first').on('afterValidate', function(e) { overlayDisplay(); });");
        }
       
        $content = "<span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;" . $content;
        return parent::submitButton($content, $options);
    }    

    /**
     * Creates a link button
     *
     */
    public static function linkButton($content = '', $link = [], $options = []) {
        if(empty($options['class'])){
            $options['class'] = 'btns pull-right greengrad';
        }
        return parent::a(parent::button($content, $options), $link);
    }

    /**
     * Creates a cancel link button
     *
     */
    public static function cancelButton($content = 'Cancel', $link = []) {
        $options['class'] = 'btns pull-right bluegrad';
        //return parent::a(parent::button($content, $options), $link);
        
        $content = "<span class='glyphicon glyphicon-remove'></span>&nbsp;&nbsp;" . $content;
        
//        $getPageLink = \yii::$app->request->referrer;
//        $session = Yii::$app->session;
//        if(strstr($getPageLink,'index&q')){
//            $customLink = explode('?r=', $getPageLink);
//            $session->set('backLink', $customLink[1]);
//        }
//        if($session->get('backLink') != ""){
//           $link[0] = str_replace('%2F', '/', trim($session->get('backLink'))); 
//        }
        
        return parent::a($content, $link, $options);
    }

    public static function disableButton($content = 'Disable', $link = []) {
        $options['class'] = 'btns pull-right whitegrad';
        return parent::a(parent::button($content, $options), $link);
    }

    /**
     * 
     * @param type $content
     * @param type $link
     * @param type $message
     * @return type
     */
    public static function confirmUpdate($content = 'Update', $options = []) {
        \yii::$app->view->registerJsFile('/js/bootstrap/bootstrap.min.js');
        \yii::$app->view->registerJsFile('/js/bootstrap/bootstrap-dialog.min.js');
        \yii::$app->view->registerJsFile('/js/bootstrap/confirmation.js');
        \yii::$app->view->registerCssFile('/css/bootstrap-dialog.min.css');

        $options['id'] = 'confirmUpdateBtn';

        return parent::button($content, $options);
    }    
    /**
     * 
     * @param type $label
     * @param type $options
     * @return type
     */
    public static function customButton($content = 'Button', $options = [])
    {
        $options['type'] = 'button';
        if(!isset($options['class'])){
           $options['class'] = 'btns pull-right greengrad';
        }
        return parent::button($content, $options);
    }
    
}
