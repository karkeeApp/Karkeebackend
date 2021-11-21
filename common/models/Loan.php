<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;
use common\helpers\LoanHelper;

class Loan extends ActiveRecord{
    const STATUS_INCOMPLETE = 0;   
    const STATUS_PENDING    = 1;  
    const STATUS_REJECTED   = 2;   
    const STATUS_PAID       = 3;
    const STATUS_DECLINED   = 4;
    const STATUS_APPROVED   = 5;

    public $total;
    public $year;
    public $month;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['loan_id'];
    }

    public static function tableName()
    {
        return '{{%user_loan}}';
    }
    
    public function getUser()
    {
        return $this->hasOne(User::classname(),['user_id' => 'user_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::classname(),['account_id' => 'account_id']);
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::update($runValidation, $attributes);
    }

    public static function create(\common\forms\LoanForm $form, \common\models\User $user)
    {
        $settings = Settings::get();

        $interestRate = (float) $settings->default_interest;

        $loan = self::find()
            ->where(['user_id' => $user->user_id])
            ->andWhere(['status' => self::STATUS_INCOMPLETE])
            ->andWhere(['=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', date('Y-m-d')])
            ->one();

        if (!$loan) {
            $loan = new self;
         
            $loan->user_id     = $user->user_id;
            $loan->account_id  = $user->account_id;
            $loan->reason      = $form->reason;
            $loan->interest    = $interestRate;
            $loan->due_date    = date('Y-m-t'); 
            $loan->status      = self::STATUS_INCOMPLETE;
        }

        $loan->amount      = $form->amount;
        $loan->save();

        return TRUE;
    }

    public static function findIncomplete(\common\forms\LoanForm $form, \common\models\User $user)
    {
        return self::find()
            ->where(['user_id' => $user->user_id])
            ->andWhere(['amount' => $form->amount])
            ->andWhere(['status' => self::STATUS_INCOMPLETE])
            ->andWhere(['=', 'DATE_FORMAT(created_at, "%Y-%m-%d")', date('Y-m-d')])
            ->one();
    }

    public function confirm()
    {
        $this->status = self::STATUS_PENDING;
        $this->approved_at = date('Y-m-t');
        $this->save();

        $this->refresh();

        /**
         * Update credit limit
         */
        $fund = $this->user->fund;
        $fund->credit_used += $this->amount;
        $fund->save();

        UserTxn::create($this->user, UserTxn::TYPE_LOAN_APPROVE, $this->amount, $this);
    }

    public function decline()
    {
        $this->status = self::STATUS_DECLINED;
        $this->save();
    }

    public function status()
    {
        switch ($this->status){
            case self::STATUS_INCOMPLETE: return '<span class="text-default">Incomplete</span>'; break;
            case self::STATUS_PENDING:
            case self::STATUS_APPROVED:
                if ($this->repaymentDate('Y-m-t') == date('Y-m-d')) {
                    return '<span class="text-danger">Due</span>'; 
                } elseif ($this->repaymentDate('Y-m-t') <= date('Y-m-d')) {
                    return '<span class="text-danger">Not Paid</span>'; 
                } else {
                    return '<span class="text-success">Not Due</span>'; 
                }
            break;
            case self::STATUS_PAID: return '<span class="text-success">Paid</span>'; break;
            case self::STATUS_DECLINED: return '<span class="text-danger">Staff Declined</span>'; break;
            case self::STATUS_REJECTED: return '<span class="text-danger">MFI Rejected</span>'; break;
        }
    }

    public static function statuses($filterOnly=FALSE)
    {
        $statuses = [
            self::STATUS_PENDING => 'Waiting for SMS',
            self::STATUS_APPROVED => 'Repayment',
            self::STATUS_PAID     => 'Paid',
            self::STATUS_REJECTED => 'MFI Rejected',
        ];

        if (!$filterOnly) {
            $statuses += [
                self::STATUS_INCOMPLETE  => 'Incomplete',
                self::STATUS_DECLINED => 'Staff Declined',
            ];
        }

        return $statuses;
    }

    public function amount()
    {
        return Common::currency((float)$this->amount);
    }

    public function appliedDate()
    {
        return Common::date($this->created_at);
    }

    public function approvedDate()
    {
        return Common::date($this->approved_at);
    }

    public function repaymentDate($format = 't-m-Y')
    {
        return Common::date($this->approved_at, $format);
    }

    public function interest()
    {
        if ($this->isPublished()) {
            return Common::Currency($this->interest / 100 * $this->amount) . ' (' . ($this->interest + 0) . '%)';
        } else {
            return '';
        } 
    }

    public function grandTotal()
    {
        return $this->amount + ($this->interest / 100 * $this->amount);
    }

    public function paidTotal()
    {
        $payment = LoanPayment::find()
            ->select('SUM(amount) AS amount')
            ->where(['loan_id' => $this->loan_id])
            ->one();

        return ($payment) ? (float)$payment->amount : 0;
    }

    public function outstanding($format = TRUE)
    {
        $outstanding = round($this->grandTotal() - $this->paidTotal(), 2);

        if (!$format) return $outstanding;
        else return Common::Currency($outstanding);
    }

    public function isIncomplete()
    {
        return $this->status == self::STATUS_INCOMPLETE;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function isPublished()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_PAID]);
    }

    public function isRepayment()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public function makePayment(\common\forms\PaymentForm $form)
    {
        $payment = new LoanPayment;
        $payment->loan_id = $this->loan_id;
        $payment->user_id = $this->user_id;
        $payment->amount = $form->amount;
        $payment->save();

        UserTxn::create($this->user, UserTxn::TYPE_LOAN_PAYMENT, (float)$form->amount * -1, $payment);

        $this->refresh();

        if ($this->outstanding(FALSE) == 0) {
            $this->status = self::STATUS_PAID;
            $this->save();

            /**
             * Return credit limit if fully paid
             */
            $fund = $this->user->fund;
            $fund->credit_used -= (float)$this->amount;
            $fund->save();
        }

        return TRUE;
    }

    public static function summaryLast12Months()
    {
        $fromDate = date('Y-m-01', strtotime('-12 month', time()));
        $toDate = date('Y-m-t');

        $result = self::find()
            ->select('SUM(amount) AS amount, YEAR(created_at) AS year, MONTH(created_at) AS month')
            ->where(['>=', 'created_at', $fromDate])
            ->andWhere(['<=', 'created_at', $toDate])
            ->groupBy([
                'year',
                'month',
            ])
            ->all();

        $summary = [];

        for($i=0; $i<12; $i++){
            $period = date('Y-m-01', strtotime("+{$i} month", strtotime($fromDate)));

            $summary[$period] = 0;
        }

        if ($result) {
            foreach($result as $row) {

                $row->month = sprintf("%02d", $row->month);
                
                $period = "{$row->year}-{$row->month}-01";
                $summary[$period] = $row->amount;
            }
        }

        return $summary;
    }

    public static function getLastLoan($user_id){
        $currentMonth = date('m');
        $result = self::find()
            ->select('*')
            ->where(['user_id' => $user_id])
            ->andWhere(['MONTH(approved_at)' => $currentMonth])
            ->all();
        $sum_total = 0;
        foreach($result as $data){
            $totalInterest = $data['amount']*$data['interest']/100;
            $total = $data['amount']+$totalInterest;
            $sum_total+=$total;
        }
        return $sum_total;
    }

    
}