<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Session;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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
     * {@inheritdoc}
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
    public function actionSetCookie(){
        $cookie = new yii\web\Cookie([
            'name' => 'prakhar',
            'value' => 'test cookie'
        ]);
        Yii::$app->getResponse()->getCookies()-add($cookie);
    }
    public function actionShowCookie(){
        if(Yii::$app->getRequest()->getCookie()->has('prakhar')){
            print_r(Yii::$app->getRequest()->getCookies()->getValue('prakhar'));
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Register action.
     *
     * @return string
     */
    public function actionRegister()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->username=$_POST['User']['username'];
                $model->email=$_POST['User']['email'];
                $model->password=password_hash($_POST['User']['password'],PASSWORD_ARGON2I);
                $model->authKey=md5(random_bytes(5));
                $model->accessToken=password_hash(random_bytes(10),PASSWORD_DEFAULT);
                if($model->save()){
                    return $this->redirect(['login']);
                }
            }
        }
        return $this->render('register',[
            'model'=>$model,
            ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $session = Yii::$app->session;
        $session->destroy();
        // $session->open();
        $session['user_id'] = Yii::$app->user->identity;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->destroy();
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionTimer(Type $var = null)
    {
        return $this->render('timer');
    }
}
