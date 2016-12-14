<?php
namespace frontend\models;

use Yii;

/**
 * Signup form
 */
class SignupForm extends \common\models\SignupForm
{
    public $userClass = 'frontend\models\User';
}
