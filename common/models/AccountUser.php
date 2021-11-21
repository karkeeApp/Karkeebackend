<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use common\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\forms\AccountUserForm;

use common\helpers\Common;

class AccountUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const ROLE_SUPERADMIN  = 1;
    const ROLE_ADMIN       = 2;
    const ROLE_MEMBERSHIP  = 3;
    const ROLE_ACCOUNT     = 4;
    const ROLE_SPONSORSHIP = 5;
    const ROLE_MARKETING   = 6;
    const ROLE_EDITOR      = 7;
    const ROLE_DEFAULT     = 8;

    const STAFF     = 1;
    const COMPANY   = 2;
    const LEAVE_ON  = 1;
    const LEAVE_OFF = 0;

    public static function tableName()
    {
        return '{{%account_user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function role()
    {
        $roles = self::roles();

        return (array_key_exists($this->role, $roles)) ? $roles[$this->role] : '';
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }

    public function company()
    {
        return $this->account->company;
    }

    public static function create(\common\forms\AccountUserForm $form, \common\models\Account $account)
    {
        $user = AccountUser::findOne($form->user_id);

        if (!$user) $user = new AccountUser;

        foreach($form->attributes as $field => $val) {
            if (!in_array($field, ['user_id', 'password'])) {
                $user->{$field} = $val;
            }
        }

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }

        $user->account_id = $account->account_id;
        $user->save();

        return $user;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

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
    public static function findByAccountUser($username, $account_id)
    {
        return static::findOne(['username' => $username, 'account_id' => $account_id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByID($user_id, $account_id)
    {
        return static::findOne(['user_id' => $user_id, 'account_id' => $account_id]);
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

    public function status()
    {
        $statuses = AccountUserForm::statuses();

        return (array_key_exists($this->status, $statuses)) ? $statuses[$this->status] : 'Unknow status';
    }

    public function isAdministrator()
    {
        return ($this->isRoleAdmin() OR $this->isRoleSuperAdmin());
    }

    public function isRoleSuperAdmin(){ return $this->role == self::ROLE_SUPERADMIN; }
    public function isRoleAdmin(){ return $this->role == self::ROLE_ADMIN; }
    public function isRoleMembership(){ return $this->role == self::ROLE_MEMBERSHIP; }
    public function isRoleAccount(){ return $this->role == self::ROLE_ACCOUNT; }
    public function isRoleSponsorship(){ return $this->role == self::ROLE_SPONSORSHIP; }
    public function isRoleMarketing(){ return $this->role == self::ROLE_MARKETING; }
    public function isRoleEditor(){ return $this->role == self::ROLE_EDITOR; }

    public function isDummy()
    {
        return $this->role == self::ROLE_DEFAULT;
    }

    public static function roles()
    {
        return [
            self::ROLE_SUPERADMIN  => 'Super Admin',
            self::ROLE_ADMIN       => 'Admin',
            self::ROLE_MEMBERSHIP  => 'Membership',
            self::ROLE_ACCOUNT     => 'Account',
            self::ROLE_SPONSORSHIP => 'Sponsorship',
            self::ROLE_MARKETING   => 'Marketing',
            self::ROLE_EDITOR      => 'Editor',            
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE  => 'Active',
            self::STATUS_DELETED => 'Deleted',
        ];
    }    
}
