<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['version1/site/reset-password', 'token' => $user->password_reset_token]);
?>
<tr>
	<td class="free-text"><?=$textBody;?></td>
</tr>
<tr>
    <td class="button">
      <div style="display: inline-block; padding-right: 5px;">
        <a class="button-mobile" href="<?= Html::encode($link) ?>" 
        style="background-color:#213A79;border-radius:5px;color:#ffffff;display:inline-block;font-family:'Cabin', Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;">Restablecer</a>
      </div>
    </td>
</tr>
