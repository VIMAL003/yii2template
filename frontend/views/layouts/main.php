<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Vimal</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="site-bg"></div>
<!-- SITE-HEADER -->
    <header class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 hidden-xs">
                    <span class="phone"><i class="fa fa-phone"></i>010-020-0340</span>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6 logo-holder">
                    <a href="#" class="site-brand">Out<span>Line</span></a>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-6 social-icons-header">
                    <ul class="social-top">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="responsive-menu visible-xs">
        <a href="#" class="toggle-menu">
            <i class="fa fa-bars"></i>
        </a> 
        <div class="show-menu">
            <ul class="main-menu">
                <li><a href="<?= Url::toRoute('/site/index');?>" class="show-1 homebutton">Home</a></li>
                <li><a href="<?= Url::toRoute('/site/index');?>#menu-2" class="show-2 aboutbutton">About Me</a></li>
                <li><a href="<?= Url::toRoute('/site/index');?>#menu-3" class="show-3 projectbutton">My Work</a></li>
                <li><a href="<?= Url::toRoute('/site/index');?>#menu-4" class="show-4 contactbutton">Stay In Touch</a></li>
            </ul>
        </div>   
    </div>
    <!-- MENU -->
    <div class="menu-bottom">
        <div class="desktop-menu hidden-xs">
            <div class="container">
                <div class="row">
                    <ul class="main-menu">
                        <li class="col-md-3 col-sm-3">
                            <a href="<?= Url::toRoute('/site/index');?>" class="show-1 homebutton">
                                <span class="menu-icon"><i class="fa fa-home"></i></span>
                                <span class="menu-text">Home</span>
                            </a>
                        </li>
                        <li class="col-md-3 col-sm-3">
                            <a href="<?= Url::toRoute('/site/index');?>#menu-2" class="show-2 aboutbutton">
                                <span class="menu-icon"><i class="fa fa-user"></i></span>
                                <span class="menu-text">About Me</span>
                            </a>
                        </li>
                        <li class="col-md-3 col-sm-3">
                            <a href="<?= Url::toRoute('/site/index');?>#menu-3" class="show-3 projectbutton">
                                <span class="menu-icon"><i class="fa fa-photo"></i></span>
                                <span class="menu-text">My Work</span>
                            </a>
                        </li>
                        <li class="col-md-3 col-sm-3">
                            <a href="<?= Url::toRoute('/site/index');?>#menu-4" class="show-4 contactbutton">
                                <span class="menu-icon"><i class="fa fa-envelope"></i></span>
                                <span class="menu-text">Stay in Touch</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="page-content">
        <div class="content-holder">
            <?= Alert::widget() ?>
            <div id="menu-container">
                    <?= $content ?>
            </div>
        </div>
    </div>

        <div class="footerdef">
           <div class="text-center">
               <p class="copyright-text">&copy; My Company <?= date('Y') ?></p>
           </div>
        </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
