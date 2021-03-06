<?php

namespace api\modules\version1\controllers;

use api\components\HelperEncrypt;
use api\models\PasswordResetRequestForm;
use api\models\ResendVerificationEmailForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\VerifyEmailForm;
use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);

        $user = new User();
        $user->attributes = $data;

        if (!$user->validate()) {
            $validate = $user->getErrors();
        }

        return array('status' => $validate);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->attributes = $data;

        if ($model->login()) {
            return array('status' => true, 'data' => Yii::$app->user->identity);
        } else {
            $model->password = '';
            return array('status' => false, 'data' => $model->getErrors());
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
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $json = Yii::$app->request->post('jsonSend');
        $model = new PasswordResetRequestForm();

        if (isset($json) && $json != '') {
            $data = json_decode($json, true);
            $model->attributes = $data;

            if ($model->validate()) {
                if ($model->sendEmail()) {
                    $mensaje = 'Se envio una notificación a la dirección de correo electrónico ' . $model->email . ', con las instrucciones.';
                    return array('status' => true, 'data' => $model->attributes, 'message' => $mensaje);
                } else {
                    $mensaje = 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico ' . $model->email . '.';
                    return array('status' => false, 'data' => $model->getErrors(), 'message' => $mensaje);
                }
            } else {
                $mensaje = 'Lo sentimos, la dirección de correo electrónico ' . $model->email . ', no tiene formato correcto.';
                return array('status' => false, 'data' => $model->getErrors(), 'message' => $mensaje);
            }
        } else {
            $mensaje = 'No se recibió ninguna dirección de correo electrónico.';
            return array('status' => false, 'data' => $model->getErrors(), 'message' => $mensaje);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword(){
        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);

        $token = HelperEncrypt::decrypt($data['token']);
        $password = $data['password'];

        if($password != ''){
            $model = User::findOne(['password_reset_token' => $token,'status' => User::STATUS_ACTIVE,]);
            $model->setPassword($password);
            $model->removePasswordResetToken();

            if($model->save()){
                $mensaje = 'Se guardo correctamente la contraseña.';
                return array('status' => true, 'data' => $model->save(), 'message' => $mensaje);
            }else{
                $mensaje = 'No guardo';
                return array('status' => false, 'data' => $model->getErrors(), 'message' => $mensaje);
            }            
        } else {
            $mensaje = 'Error con la información suministrada.';
            return array('status' => false, 'data' => $model->getErrors(), 'message' => '----');
        }
    }

    public function actionValidateTokenPassword(){

        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);
        $token = HelperEncrypt::decrypt($data);

        if ($token == true) {
            try {
                $model = new ResetPasswordForm($token);
                $mensaje = 'Token validado';
                return array('status' => true, 'data' => $token, 'message' => $model);

            } catch (InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
                $mensaje = 'Token no valido';
                return array('status' => false, 'data' => $e->getMessage(), 'message' => $mensaje);
            }
        } else {
            $mensaje = 'Token no valido';
            return array('status' => false, 'data' => $data, 'message' => $mensaje);
        }

    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model,
        ]);
    }

}
