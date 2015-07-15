<?php

use im\users\models\Token;

$time = time();

return [
    'confirmation' => [
        'user_id' => 2,
        'token' => 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6',
        'type' => Token::TYPE_REGISTRATION_CONFIRMATION,
        'created_at' => $time,
        'expire_at' => $time + 3600,
    ],
    'expired_confirmation' => [
        'user_id' => 3,
        'token' => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
        'type' => Token::TYPE_REGISTRATION_CONFIRMATION,
        'created_at' => $time - 3600,
        'expire_at' => $time - 1800,
    ],
    'expired_recovery' => [
        'user_id' => 5,
        'token' => 'a5839d0e73b9c525942c2f59e88c1aaf',
        'type' => Token::TYPE_RECOVERY,
        'created_at' => $time - 21601,
        'expire_at' => $time,
    ],
    'recovery' => [
        'user_id' => 6,
        'token' => '6f5d0dad53ef73e6ba6f01a441c0e602',
        'type' => Token::TYPE_RECOVERY,
        'created_at' => $time,
        'expire_at' => $time,
    ]
];