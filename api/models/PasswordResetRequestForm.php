<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model{

    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'No hay usuario con esta dirección de correo electrónico.',
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail(){
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app->mailer->compose(
            ['html' => 'email_html'],
            [
                'imgLogo' => Yii::getAlias('@api/web/img/entregalo.png'),
                'headMailText' => "Estimado usuario ",
                'textBody' => "Por favor haga clic en el botón restablecer, será llevado a un formulario donde podrá registrar una nueva contraseña y continuar utilizando la aplicación",
                'bodyMail' => 'passwordResetToken-html',
                'link' => 'localhost:4200/resettokenpass/'.$this->encrypt($user->password_reset_token),
                //'link' => Yii::$app->urlManager->createAbsoluteUrl(['localhost:4200/resettokenpass/', 'params' => $this->encrypt($user->password_reset_token)]),
            ]
        )
            ->setTo($this->email)
            ->setFrom([Yii::$app->params["supportEmail"] => Yii::$app->name])
            ->setSubject('Recuperación de contraseña ' . Yii::$app->name)
            ->send();

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

}
