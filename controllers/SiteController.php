<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter; 
use app\models\ContactForm;
use app\models\JobsSearch;

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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
           

            return $this->render('index',[
              
                

            ]);
        } else {
            return $this->redirect(['user-management/auth/login']);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            return $this->redirect(['user-management/auth/login']);
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
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

    public function actionUserLocations()
    {
        $sql =" SELECT g.lat , g.lng , u.username , u.first_name , g.updated_at FROM tbl_geo_location g INNER JOIN user u on u.id = g.user_id";
       $Users = Yii::$app->db->createCommand($sql)->queryAll();
       return \yii\helpers\Json::encode($Users);  
    }


    public function actionUserLocationById()
    {
        $user_id =  $_POST['id'] ; 

       $sql =" SELECT g.lat , g.lng , u.username , u.first_name , g.updated_at FROM tbl_geo_location g INNER JOIN user u on u.id = g.user_id  WHERE g.user_id = ".$user_id ;
       $Users = Yii::$app->db->createCommand($sql)->queryAll();
       return \yii\helpers\Json::encode($Users);  
    }

    
}
