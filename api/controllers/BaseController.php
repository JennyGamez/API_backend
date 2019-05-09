<?php

/**
 * @Author: amosquera
 * @Date:   2018-02-15 07:32:27
 * @Last Modified by:   amosquera
 * @Last Modified time: 2018-03-01 09:44:06
 */

namespace api\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller {

    public function beforeAction($action) {
        parent::beforeAction($action);

        $operacion = str_replace("/", "%", Yii::$app->controller->route);

        $validacion = false;
        foreach (Yii::$app->user->identity->rol->rolesTiposOperaciones as $key => $val) {
            if ($val->rolOperacion->nombreRolOperacion == $operacion) {
                $validacion = true;
                break;
            }
        }

        //return array('status' => false, 'mensaje' => Yii::$app->controller->route);
        
        if ($validacion) {
            return true;
        } else {
            $action->actionMethod = "actionAccessDenied";
            return parent::beforeAction($action);
        }
    }

    public function actionAccessDenied() {
        return array('status' => false, 'mensaje' => 'No autorizado');
    }

    public function numberFormat($num) {
        return str_replace(array(" ", "$", "."), "", $num);
    }

    public function soloLetrasNumeros($text) {
        return str_replace(array(" ", "$", ".", "%", "/", "-", "\t", "\r", "\n"), "", $text);
    }

    public function encrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'llaveEncriptacion';
        $secret_iv = 'vectorInicializacion';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function decrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'llaveEncriptacion';
        $secret_iv = 'vectorInicializacion';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

}

?>