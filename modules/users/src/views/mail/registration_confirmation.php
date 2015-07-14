<?php

use im\users\Module;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user im\users\models\User */
/* @var $token im\users\models\Token */

?>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('registration', 'Hello') ?>, <?= $user->profile->first_name ?>
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('registration', 'Thank you for registration on {0}', Yii::$app->name) ?>.
    <?= Module::t('registration', 'In order to complete your registration, please click the link below') ?>.
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Url::to(['/users/registration/confirm', 'token' => $token->token], true); ?>
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('registration', 'If you cannot click the link, please try pasting the text into your browser') ?>.
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('registration', 'If you did not make this request you can ignore this email') ?>.
</p>
