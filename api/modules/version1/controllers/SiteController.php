<?php

namespace api\modules\version1\controllers;

use Yii;
use common\models\LoginForm;
use common\models\User;
use api\models\ResendVerificationEmailForm;
use api\models\VerifyEmailForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use api\controllers\BaseController;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\base\InvalidArgumentException;

/**
 * Site controller
 */
class SiteController extends Controller {

    public function behaviors(){
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
    public function actionIndex() {
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
    public function actionLogin() {
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
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
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
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
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
    public function actionRequestPasswordReset() {
        $json = Yii::$app->request->post('jsonSend');
        $data = json_decode($json, true);

        $model = new PasswordResetRequestForm();
        $model->attributes = $data;

        if ($model->validate()) {
            if ($model->sendEmail()) {
                $status = true;
                $validaciones = '';
                $mensaje = 'Revise su correo electrónico para obtener más instrucciones.';
            } else {
                $status = false;
                $validaciones = $model->getErrors(); 
                $mensaje = 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico.';
            }
        }else{
            $status = false;
            $validaciones = $model->getErrors(); 
            $mensaje = 'Lo sentimos, no llego el correo';
        }
        return array('status' => $status, 'data' => $validaciones, 'mensaje' => $mensaje);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
            $status = true;
            $validaciones = $model->getErrors();
            $mensaje = 'valido';

        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
            $status = false;
            $validaciones = $e->getMessage();
            $mensaje = 'Token no valido';

        }
        if ($model->validate()) {
            $validaciones = '';
            $status = true;
            $mensaje ='New password saved.';
        }

        return array('status' => $status, 'data' => $validaciones, 'mensaje' => $model->attributes);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token) {
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
    public function actionResendVerificationEmail() {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
                    'model' => $model
        ]);
    }

}
