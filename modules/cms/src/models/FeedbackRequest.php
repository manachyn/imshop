<?php

namespace im\cms\models;

use im\cms\Module;
use im\forms\models\FormRequest;

/**
 * Class FeedbackRequest
 *
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $text
 *
 * @package im\cms\models
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FeedbackRequest extends FormRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['phone', 'email'], 'required'],
            [['name', 'phone', 'text'], 'string'],
            [['email'], 'email']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('feedback-request', 'ID'),
            'name' => Module::t('feedback-request', 'Name'),
            'email' => Module::t('feedback-request', 'Email'),
            'phone' => Module::t('feedback-request', 'Phone'),
            'text' => Module::t('feedback-request', 'Text'),
            'created_at' => Module::t('gallery', 'Created At'),
            'updated_at' => Module::t('gallery', 'Updated At')
        ];
    }
}