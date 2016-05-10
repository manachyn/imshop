<?php

use im\users\Module;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var im\users\models\User $user */
/* @var im\users\models\Token $token */

?>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('recovery', 'Hello') ?> <?= $user->profile->first_name ?>,
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Module::t('recovery', 'Please click the link below to reset your password') ?>.
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Url::to(['/users/recovery/reset', 'token' => $token->token], true); ?>
</p>