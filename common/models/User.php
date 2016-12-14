<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\overrides\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $role
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $changePassword = false;
    public $oldpass;
    public $newpass;
    public $repeatnewpass;
    public $oldpassHash;
    public $changePasswordFlag = true;
    public $namespace = 'common\models';

    const ROLE_SUPER_ADMIN = 35;
    const ROLE_ADMIN = 30;
    const ROLE_DOCTOR = 25;
    const ROLE_PATIENT = 20;
    const ROLE_NURSE = 15;
    const ROLE_PHARMACIST = 10;
    const ROLE_LABORATORIST = 5;
    const ROLE_ACCOUNTANT = 3;
    const ROLE_RECEPTIONIST = 1;
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 10;

    public static $roles = [
         self::ROLE_ADMIN =>  'admin',
         self::ROLE_DOCTOR  =>  'doctor',
         self::ROLE_PATIENT  =>  'patient',
         self::ROLE_NURSE  => 'nurse',
         self::ROLE_PHARMACIST  =>  'pharmacist',
         self::ROLE_LABORATORIST  =>   'laboratorist',
         self::ROLE_ACCOUNTANT => 'accountant',
         self::ROLE_RECEPTIONIST  => 'receptionist'
    ];

    public static $statuses = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_PENDING => 'inactive'
    ];

    public function __construct( $config = null )
    {
        parent::__construct( $config );
        $this->roles = array_keys( self::$roles );
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getEntity()
    {
        $className = ucfirst(self::$roles[$this->role]);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $this->id])->one();
    }

    public static function getEntityStatic($id)
    {
        $user = self::findOne($id);
        $className = self::$roles[$user->role];
        $className = ucfirst($className);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $id])->one();
    }

    public static function dropDownAvailableUsers($role)
    {
        $usersDropDown = [];
        $data = User::find()->where(['role' => $role, 'status' => self::STATUS_PENDING])->asArray()->all();

        if(count($data)) {
            foreach ($data as $key => $value) {
                $usersDropDown[$value['id']] = $value['username'];
            }
        }
        return $usersDropDown;
    }

    public static function dropDownAvailableRoles($role)
    {
        switch ($role) {

            case self::ROLE_ADMIN:
                $availableRolesDropDown = User::$roles;
                break;

            case self::ROLE_DOCTOR:
                $availableRolesDropDown = [self::ROLE_PATIENT  =>  'patient'];
                break;

            case self::ROLE_NURSE:
                $availableRolesDropDown = [self::ROLE_PATIENT  =>  'patient'];
                break;

            default:
                $availableRolesDropDown =[];
        }

        return $availableRolesDropDown;
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verification_token' => [
                    'class' => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => 'verification_token',
                    ],
                    'value' => function() {
                        return Yii::$app->security->generateRandomString();
                    },
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash', 'email','role','status'],'required'],
            ['username','string'],
            ['email', 'email'],
            ['password_hash', 'string'],

            [
                'username',
                'unique',
                'targetAttribute'=>'username',
                'targetClass'=> 'common\models\User',
                'when' => function ($model) {
                    return $model->isAttributeChanged('username');
                }
            ],

            [
                'email',
                'unique',
                'targetAttribute'=>'email',
                'targetClass'=> 'common\models\User',
                'when' => function ($model) {
                    return $model->isAttributeChanged('email');
                }
            ],

            ['role', 'integer'],
            ['role', 'in', 'range' => $this->roles],

            ['status','integer'],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_PENDING] ],

            [
                ['oldpass', 'newpass', 'repeatnewpass'],
                'required',
                'when' => function ($model) {
                    return $this->changePassword;
                }
            ],

            ['oldpass','findPasswords'],
            ['newpass','string'],
            [
                'repeatnewpass',
                'compare',
                'compareAttribute'=>'newpass',
                'message'=>'Please enter the same password in New password and Repeat new password fields'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function entityCreate()
    {
        switch ($this->role) {

            case self::ROLE_ADMIN:
                $entityModel = new Admin();
                $entityModel->system_name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_DOCTOR:
                $entityModel = new Doctor();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_NURSE:
                $entityModel = new Nurse();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_PATIENT:
                $entityModel = new Patient();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->birth_date = date("Y-m-d", $this->created_at);
                $entityModel->save();
                break;

            case self::ROLE_PHARMACIST:
                $entityModel = new Patient();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_RECEPTIONIST:
                $entityModel = new Patient();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_LABORATORIST:
                $entityModel = new Patient();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            case self::ROLE_ACCOUNTANT:
                $entityModel = new Accountant();
                $entityModel->name = $this->username;
                $entityModel->user_id = $this->id;
                $entityModel->save();
                break;

            default:
                echo 'invalid User role'; exit;
        }
    }

    /**
     * @inheritdoc
     */
    public function findPasswords($attribute)
    {
        if ($this->changePasswordFlag){

            if ($this->validatePassword($this->oldpass)) {
                // all good, logging user in
            } else {
                // wrong password
                $this->addError($attribute, 'Sorry, but old password is incorrect');
            }

        }
    }

    /**
     * @inheritdoc
     */
    public function setRoles($data){
        $this->roles = $data;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $login
     * @param integer $status
     * @return static|null
     */
    public static function findByLogin($login, $status = null)
    {
        $userQuery = static::find()->andWhere(['or', ['username' => $login], ['email' => $login]]);
        if ($status) {
            $userQuery->andWhere(['status' => $status]);
        }
        return $userQuery->limit(1)->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $status = self::STATUS_ACTIVE)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        $userQuery = static::find()->andWhere(['password_reset_token' => $token]);
        $userQuery->andFilterWhere(['status' => $status]);
        return $userQuery->limit(1)->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function changeStatus()
    {
        $editableStatus = $this->status;
        if($editableStatus === self::STATUS_PENDING ) $this->status = self::STATUS_ACTIVE;
        if($editableStatus === self::STATUS_ACTIVE ) $this->status = self::STATUS_PENDING;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSchedule()
    {
        return $this->hasMany(UserSchedule::className(), ['user_id' => 'id']);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public static function getStatusTexts()
    {
        $statusTexts = parent::getStatusTexts();
        $statusTexts[static::STATUS_PENDING] = Yii::t('app', 'Pending');
        return $statusTexts;
    }

    /**
     * @param $verification_token
     * @param $status
     * @return null|User Referral
     */
    public static function findByVerificationToken($verification_token,  $status = self::STATUS_PENDING)
    {
        $userQuery = static::find()->andWhere(['verification_token' => $verification_token]);
        $userQuery->andFilterWhere(['status' => $status]);
        return $userQuery->limit(1)->one();
    }

    /**
     * Activate user
     *
     * @param bool $validate
     * @return bool
     */
    public function activate($validate = true)
    {
        $this->verification_token = null;
        $this->status = static::STATUS_ACTIVE;
        return $this->save($validate);
    }

    public function deactivate($validate = true)
    {
        $this->verification_token = null;
        $this->status = static::STATUS_PENDING;
        return $this->save($validate);
    }

    /**
     * @param bool $resend
     * @return bool If mail send was successful
     */
    public function sendVerificationEmail($resend = false)
    {
        // TODO: add processing in case of resend (e.g. change subject)
        return Yii::$app->mailer
            ->compose(
                [
                    'html' => 'resendVerification-html',
                    'text' => 'resendVerification-text'
                ],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Email verification on ' . Yii::$app->name)
            ->send();
    }
}
