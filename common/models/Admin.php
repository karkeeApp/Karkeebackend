<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use common\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Admin model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 1;

    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN      = 2;
    const ROLE_MANAGER    = 3;
    const ROLE_EDITOR     = 4;
    
    public $account_id   = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function getSettings()
    {
        $settings = HRSettings::find()->where(['admin_id' => $this->admin_id])->one();

        if (!$settings) {
            $settings = new HRSettings;
            $settings->admin_id = $this->admin_id;
            $settings->save();
        }

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['admin_id' => $id, 'status' => self::STATUS_ACTIVE]);
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
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
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
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
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

    public function isAdministrator()
    {
        return TRUE;
    }

    public function isDummy()
    {
        return TRUE;
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETED => 'Deleted',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->role == self::ROLE_SUPERADMIN;
    }

    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function role()
    {
        $roles = self::roles();

        return array_key_exists($this->role, $roles) ? $roles[$this->role] : NULL;
    }

    public static function roles()
    {
        return [
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_ADMIN      => 'Admin',
            self::ROLE_MANAGER    => 'Manager',
            self::ROLE_EDITOR     => 'Editor',
        ];
    }
    
    public static function create(\cpanel\forms\AdminForm $form)
    {
        $admin = Admin::findOne($form->admin_id);

        if (!$admin) $admin = new Admin;

        foreach($form->attributes as $field => $val) {
            if (!in_array($field, ['admin_id', 'password'])) {
                $admin->{$field} = $val;
            }
        }

        if (!empty($form->password)) {
            $admin->setPassword($form->password);
        }

        $admin->generateAuthKey();
        $admin->save();

        return $admin;
    }
}
