<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
$model = new  frontend\models\SearchForm();
?>
<!-- HOMEPAGE -->
    <div id="menu-1" class="homepage home-section text-center">
        <h1>FREE Responsive Template</h1>
        <p class="description"><a href="#">Outline HTML5 Template</a> is free responsive layout by <span class="blue">template</span><span class="green">mo</span>. You can download, modify and apply this layout for any of your website. Credits go to <a rel="nofollow" href="http://unsplash.com">Unsplash</a> for images.</p>

            <?php $form = ActiveForm::begin(['options'=>['class'=>'subscribe-form']]); ?>
                <h3></h3>
                <fieldset class="email-holder">
                    <?= $form->field($model, "city")->widget(Select2::classname(), [ 
                            'data' => $model::getCityList(),
                            'language' => 'en',
                            'options' => ['placeholder' => Yii::t('app', 'Select your city here...') ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                    ])->label(false); ?>
                   <?= 
                    $form->field($model, "area")->widget(DepDrop::classname(), [
                          'type'=>DepDrop::TYPE_SELECT2,
                          'select2Options'=>[
                              'pluginOptions'=>[
                                  'allowClear'=>true, 
                              ],
                          ],
                          'pluginOptions'=>[
                              'depends'=>['searchform-city'],
                              'url'=>Url::to(['/site/get-area']),
                              'placeholder'=>Yii::t('app', 'Selct your area here...')
                          ],
          
                  ])->label(false); ?>
                </fieldset>
                <fieldset class="button-holder">
                    <input type="submit" class="btn light" value="Go for It">
                    <!--<a href="#" class="btn default">Learn More</a>-->
                </fieldset>
                <p class="small">Get our latest updates and news in your inbox!</p>
            <?php ActiveForm::end(); ?>
       
    </div>
<!-- END HOMEPAGE -->