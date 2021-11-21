<?php
namespace common\helpers;

use Yii;
use common\models\Loan;

class LoanHelper
{
    const DEFAULT_INTEREST_AMOUNT = .01;

    public static function calculate($dateApplied, $amount=0 , $interestRate=0)
    {
        $dateApplied = strtotime($dateApplied);

        $date1 = date_create(date('Y-m-d', $dateApplied));
        $date2 = date_create(date('Y-m-t', $dateApplied));
        $diff = date_diff($date1,$date2);

        $totalDays = date('t', $dateApplied);
        $days = $diff->days;

        $interestPerDay = round(($amount * $interestRate / 100) / $totalDays, 2);
        $interest = $interestPerDay * $days;

        if ($interest <= 0) $interest = self::DEFAULT_INTEREST_AMOUNT;

        return [
            'totalDays'      => $totalDays,
            'days'           => $days,
            'interestPerDay' => $interestPerDay,
            'interest'       => $interest,
        ];
    }

    public static function currentLoanSummary($qry)
    {
        $qry = clone($qry);

        $summary = $qry->select('SUM(amount) AS amount, SUM(IF((ROUND((amount * interest), (2))) = 0, (.01), (amount * interest)) / 100) AS interest')
            ->andWhere(['YEAR(user_loan.created_at)' => DATE('Y')])
            ->andWhere(['MONTH(user_loan.created_at)' => DATE('m')])
            ->one();
        
        $result = [
            'amount' => 0,
            'interest' => 0,
        ];

        if ($summary) {
            $result = [
                'amount' => $summary->amount,
                'interest' => $summary->interest,
            ];
        }

        return $result;
    }
}