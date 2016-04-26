
<?php
defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_ENV') || define('YII_ENV', 'dev');

defined('YII_ENV_DEFAULT') || define('YII_ENV_DEFAULT', 'local');

set_time_limit(0);

//require(__DIR__ . '/../../vendor/autoload.php');
//require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
//require(__DIR__ . '/../../common/config/bootstrap.php');
//require(__DIR__ . '/../config/bootstrap.php');
//
//$config = yii\helpers\ArrayHelper::merge(
//    require(__DIR__ . '/../../common/config/main.php'),
//    require(__DIR__ . '/../../common/config/main-local.php'),
//    require(__DIR__ . '/../config/main.php'),
//    require(__DIR__ . '/../config/main-local.php')
//);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require_once(__DIR__ . '/../common/config/bootstrap.php');
require_once(__DIR__ . '/../frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require_once(__DIR__ . '/../common/config/main.php'),
    require_once(__DIR__ . '/../common/config/main-local.php'),
    require_once(__DIR__ . '/../frontend/config/main.php'),
    require_once(__DIR__ . '/../frontend/config/main-local.php')
);

function pr($arr)
{
   echo '<pre>';
   print_r($arr);
   echo '</pre>'; 
}

$application = new yii\web\Application($config);
Yii::$app->language = 'en';
$application->run();

