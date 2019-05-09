<?php

namespace api\modules\version1\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `version1` module
 */
class DefaultController extends Controller {

    

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        //return $this->render('index');
        return array('data' => 1);
    }

}
