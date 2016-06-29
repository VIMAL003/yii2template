<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <fieldset>
                        <div class="form-group">
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                        <div class="checkbox">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </fieldset>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
