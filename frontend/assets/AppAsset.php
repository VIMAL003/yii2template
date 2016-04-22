<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $css = [
//        'css/site.css',
//    ];
    public $css = [
        'css/normalize.css',
        'css/font-awesome.css',
        'css/bootstrap.min.css',
        'css/templatemo-style.css',
        'css/developer.css',
    ];
    public $js = [
        'js/vendor/modernizr-2.6.2.min.js',
        'js/min/plugins.min.js',
        'js/custom.js',
        'js/main.js',
        //'js/min/main.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
