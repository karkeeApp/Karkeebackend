<?php
namespace common\helpers;

use Yii;
use common\models\User;
use common\models\Loan;
use common\models\LeaveHoliday;
use common\models\LeaveApplication;
use common\models\AccountUser;

class Helper{
	public static function staff($id, $throw = TRUE)
	{
		$user = self::findStaff()->andWhere(['user_id' => $id])->one();

		if (!$throw) return $user;

        if (!$user) {
            throw new \yii\web\HttpException(404, 'User not found.');
        }

        return $user;
	}

	/**
     * This function is for admin only
     */
    public static function findStaff($account = NULL)
    {
        if (!$account AND Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
            $account = $hr->account;
        }

        return User::find()->where(['account_id' => $account->account_id]);
    }

    public static function loan($id, $throw = TRUE)
    {
        $loan = self::findLoan()->andWhere(['loan_id' => $id])->one();

        if (!$throw) return $loan;

        if (!$loan) {
            throw new \yii\web\HttpException(404, 'Loan not found.');
        }

        return $loan;
    }

    /**
     * This function is for admin only
     */
    public static function findLoan($account = NULL)
    {
        if (!$account AND Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
            $account = $hr->account;
        }

        return Loan::find()->innerJoin('user', 'user.user_id = user_loan.user_id')->where(['user_loan.account_id' => $account->account_id]);
    }

    public static function holiday($id, $throw = TRUE)
    {
        $holiday = self::findHoliday()->andWhere(['holiday_id' => $id])->one();

        if (!$throw) return $holiday;

        if (!$holiday) {
            throw new \yii\web\HttpException(404, 'Holiday not found.');
        }

        return $holiday;
    }

    public static function findHoliday($account = NULL)
    {
        if (!$account AND Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
            $account = $hr->account;
        }

        return LeaveHoliday::find()->where(['account_id' => $account->account_id]);        
    }

    public static function leave($id, $throw = TRUE)
    {
        $leave = self::findLeave()->andWhere(['leave_id' => $id])->one();

        if (!$throw) return $leave;

        if (!$leave) {
            throw new \yii\web\HttpException(404, 'Leave not found.');
        }

        return $leave;
    }

    public static function findLeave($account = NULL)
    {
        if (!$account AND Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
            $account = $hr->account;
        }

        return LeaveApplication::find()->where(['account_id' => $account->account_id]);        
    }

    public static function hr($id, $throw = TRUE)
    {
        if (Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
        } else {
            $hr = AccountUser::findOne($id);
        }

        if (!$throw) return $hr;

        if (!$hr) {
            throw new \yii\web\HttpException(404, 'HR not found.');
        }

        return $hr;
    }

    public static function admin($id, $throw = TRUE)
    {
        if (Common::isClub()) {
            $user = Yii::$app->user->getIdentity();
        } else {
            $user = AccountUser::findOne($id);
        }

        if (!$throw) return $user;

        if (!$user) {
            throw new \yii\web\HttpException(404, 'Admin not found.');
        }

        return $user;
    }

    public static function findAdmin($account = NULL)
    {
        if (!$account AND Common::isClub()) {
            $user = Yii::$app->user->getIdentity();
            $account = $user->account;
        }

        return User::find()
            ->where(['account_id' => $account->account_id])
            ->andWhere('(role IS NOT NULL AND role > 0)');
    }
}