<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Order */
/* @var $form ActiveForm */
$model = new frontend\models\Order();
?>
<!-- CONTACT -->
<div id="menu-5" class="order-section">
    <div class="text-center">
        <div class="page-top">
            <h1 class="page-title">My Information</h1>
        </div>
        <h2 class="subtitle">We will contact to you from here.</h2>
    </div>
    <div class="row">
        <?php $form = ActiveForm::begin(['validateOnBlur' => false,'action'=>Url::toRoute('/site/order'),'options'=>['class'=>'contact-form order-form']]); ?>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="input-holder">
                            <label for="name">Name</label>
                            <?= $form->field($model, 'name')->label(false); ?>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="input-holder">
                            <label for="email">Address</label>
                            <?= $form->field($model, 'address')->textarea(['rows' => 3])->label(FALSE); ?>

                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="input-holder">
                            <label for="message">Mobile</label>
                            <?= $form->field($model, 'mobile')->label(FALSE); ?>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="input-holder">
                            <label for="message">Confirm Mobile</label>
                            <?= $form->field($model, 'confirm_mobile')->label(FALSE); ?>
                        </fieldset>
                    </div>
                   
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <fieldset class="input-holder">
                        <label for="name">Selected Stall</label>
                        <ul id="orderStallList" class="stallnameList">
                            <li>Vimal Patel</li>
                            <li>Vimal Patel</li>
                            <li>Vimal Patel</li>
                        </ul>
                    </fieldset>
                </div>
                
            </div>
            <div class="col-md-12">
                <fieldset class="input-holder">
                    <a id="backBtn" class="btn default" href="javascript:void(0);">
                       <span class="menu-text">Back</span>
                    </a>
                    <?= Html::submitButton(Yii::t('app', 'Get Order'), ['id'=>'submitBtn','class' => 'btn default']) ?>
                </fieldset>
            </div>
            <input type="hidden" id="selectStallVal" value="0" />
           
        <?php ActiveForm::end(); ?>
        
    </div>
</div>
<?php 

echo $this->registerJs("$('document').ready(function(){ "
        . "$('#backBtn').on('click',function(){ "
        . "$('#menu-5').hide('slow');$('#storeListMenu').show('slow');"
        . "}); "
        . "});");

?>
<!-- END CONTACT -->