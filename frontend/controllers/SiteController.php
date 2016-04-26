<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public $searchModel;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->searchModel = new \frontend\models\SearchForm();
        if ($this->searchModel->load(Yii::$app->request->post()) && $this->searchModel->validate()) {
            Yii::$app->session->setFlash('contact-us', 'Thank you for contacting us. We will respond you soon.');
            return $this->redirect(['site/getstore','id' =>$this->searchModel->area]);
        }
        return $this->render('index');
    }
    public function actionGetstore($id){
       // pr(Yii::$app->request->post());exit;
        $stallObj = new \frontend\models\Stall();
        //$stallData = $stallObj->find()->where('area_id = '.$id)->asArray()->all();
        $stallData = $stallObj->find()->where('area_id = '.$id)->all();
        return $this->render('storelist',['stallData'=>$stallData]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * 
     * @return type
     */
    public function actionOrder()
    {
        $modelOrder = new \frontend\models\Order();
        $modelOrderStall = new \frontend\models\OrderStall();
        if ($modelOrder->load(Yii::$app->request->post())) {
            $postData = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $modelOrder->save(false)) {
                    $i = 0;
                    foreach ($postData['selectedStall'] as $stallId) {
                        if ($flag === false) {
                            break;
                        }
                        $modelOrderStall = new \frontend\models\OrderStall();
                        $modelOrderStall->order_id = $modelOrder->id;
                        $modelOrderStall->stall_id = $stallId;
                        $i++;
                        if (!($flag = $modelOrderStall->save(false))) {
                            break;
                        }
                    }

                }
                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Order has been created. We will contact to your register mobile number soon..'));
                    return Yii::$app->getResponse()->redirect(['site/index']);
                }else {
                    $transaction->rollBack();
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        }

       // return $this->render('index');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    /**
     * return City Data
     */
    public function actionGetArea() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null && $parents[0] != "") {
                $city_id = $parents[0];
                $data = \frontend\models\CityArea::getCityArea($city_id);
                $i = 0;
                foreach ($data as $key => $value) {
                    $out[$i] = ['id' => $key, 'name' => $value];
                    $i++;
                }

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
}
