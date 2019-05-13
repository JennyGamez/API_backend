<?php

namespace api\modules\version1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth; 
use yii\filters\auth\HttpBearerAuth; 
use yii\filters\auth\QueryParamAuth; 
use yii\filters\auth\CompositeAuth; 

/**
 * Default controller for the `version1` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create', 'index', 'update'],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBasicAuth::className(),
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //return $this->render('index');
        return array('data' => Yii::$app->user->identity);    
    }

}
