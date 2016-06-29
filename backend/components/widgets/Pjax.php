<?php
namespace backend\components\widgets;

use yii\widgets\Pjax as DefaultPjax;

class Pjax extends DefaultPjax
{   
    
    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        
        $id = $this->options['id'];
        $js = <<<EOF
                $(document).ready(function () {

                    $('#$id').on("pjax:send", function () {        
                        $("table").prepend("<div id='overlay' class=\"overlay\"></div>");
                        $("#overlay").css({
                            "background": "rgba(0, 0, 0, 0.2) url('img/loader2.gif') no-repeat center center",
                            "position": "absolute",
                            "width": $("table").width(),
                            "height": $("table").height() - 35,
                            "z-index": 99999,
                        }).fadeTo(0, 0.6);
                    });

                    $('#$id').on("pjax:complete", function () {
                        $("#overlay").remove();
                
                        if (typeof reloadScript === "function")
                        {
                            reloadScript();
                        }                
                    });
                });

                $(document).on('pjax:timeout', function(event) {
                  // Prevent default timeout redirection behavior
                  event.preventDefault()
                })                  
EOF;
        
        $view = $this->getView();
        $view->registerJs($js);
    }
    
}