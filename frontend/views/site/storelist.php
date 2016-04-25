<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\widgets\ActiveForm;
 //\yii::$app->view->registerCssFile('css/developer.css');
?>
<!-- GALLERY -->
<div  class="gallery-section">
    <div class="text-center">
        <div class="page-top">
            <h1 class="page-title">My Stall</h1>
        </div>
        <h2 class="subtitle">Stall in your area..</h2>
    </div>
    <div class="row">
        <?php if(!empty($stallData)){
                foreach ($stallData as $stall){ ?>
            <div class="col-md-4 col-sm-6">
                <div class="project-block">
                    <div class="thumb-it">
                        <a href="javascript:void(0)"><img src="img/3.jpg" alt=""></a>
                    </div>
                    <div class="block-content" id="<?= $stall->name; ?>">
                        <h3><a href="javascript:void(0)"><?= "order from ".$stall->name; ?></a></h3>
                    </div>
                </div>
            </div>
        <?php } ?>
            <div class="col-md-12">
               <fieldset class="input-holder">
                   <a class="btn default" href="/index.php?r=site%2Findex#menu-5">
                       <span class="menu-text">Next</span>
                   </a>
               </fieldset>
            </div>
         <?php }else{ ?>
            <div class="col-md-4 col-sm-6">
                <div class="project-block">
                    <div class="thumb-it">
                        <a href="javascript:void(0)"><img src="img/5.jpg" alt=""></a>
                    </div>
                    <div class="block-content">
                        <h3><a href="javascript:void(0);">No Stall Available</a></h3>
                    </div>
                </div>
            </div>
        <?php } ?>
        
        
    </div>
</div>


<!-- END GALLERY -->