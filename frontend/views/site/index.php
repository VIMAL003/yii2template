<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Vimal - Home');
$this->params['breadcrumbs'][] = $this->title;

 //\yii::$app->view->registerCssFile('css/flexslider.css');
 
// \yii::$app->view->registerJsFile('/js/home.js', ['depends' => [\yii\web\JqueryAsset::className()]]); 
?>

<?php echo $this->render('/site/home', []); ?>

<?php echo $this->render('/site/about', []); ?>

<?php echo $this->render('/site/gallary', []); ?>

<?php echo $this->render('/site/contacttemplate', []); ?>

<?php //echo $this->render('/site/order', []); ?>

