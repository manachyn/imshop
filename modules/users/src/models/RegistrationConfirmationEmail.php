<?php

namespace im\users\models;

use yii\base\Model;

class RegistrationConfirmationEmail extends Model
{
    public $mailer;
    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string|boolean HTML layout view name. This is the layout used to render HTML mail body.
     * The property can take the following values:
     *
     * - a relative view name: a view file relative to [[viewPath]], e.g., 'layouts/html'.
     * - a path alias: an absolute view file path specified as a path alias, e.g., '@app/mail/html'.
     * - a boolean false: the layout is disabled.
     */
    public $htmlLayout = 'layouts/html';

    /**
     * @var string|boolean text layout view name. This is the layout used to render TEXT mail body.
     * Please refer to [[htmlLayout]] for possible values that this property can take.
     */
    public $textLayout = 'layouts/text';

    /**
     * @var string string the directory that contains the view files for composing mail messages
     * Defaults to '@app/mail'.
     */
    protected $viewPath;

    public $view = ['html' => 'registration_confirmation', 'text' => 'text/registration_confirmation'];
}