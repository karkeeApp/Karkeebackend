<?php
namespace common\helpers;

use Yii;
use yii\httpclient\Client;

use common\models\User;
use common\models\UserNotification;

use common\helpers\Common;

class UserHelper
{
	public static function getUser($id)
    {
        if (Common::isHR()) {
            $data['user'] = HRHelper::staff($id);
        } elseif (Common::isStaff()) {
            $data['user'] = Yii::$app->user->getIdentity();
        } else {
            $data['user'] = User::findOne($id);

            if (!$data['user']) {
                throw new \yii\web\HttpException(404, 'User not found.');
            } 
        }

        return $data;
    }

	public static function calculateScore(\common\models\User $user, $save = TRUE)
	{
		$data = [
			'user' => $user->attributes + ['total_loans' => $user->loanCount],
			'account' => $user->account->attributes,
			'fund' => ($user->fund) ? $user->fund->attributes : [],
		];

		$client = new Client();

		$response = $client->createRequest()
		    ->setMethod('post')
		    ->setUrl(Yii::$app->params['score_http'])
		    ->setData($data)
		    ->send();

		$response = json_decode($response->content, TRUE);

		$creditLimit = 0;

		if ($save) {
			$user->score = $response['score'];
			$creditLimit = $response['creditLimit'];

			$user->save(FALSE);

			$fund = $user->fund;
			$fund->credit_limit = $creditLimit;
			$fund->save();
		}

		$score['creditLimit'] = $creditLimit;

		return $score;
	}

	public static function pendingNotifications($limit=10)
	{
		$result = [
			'count' => 0,
			'content' => '',
		];

		if (!Common::isStaff()) return $result;

		$data['notifications'] = UserNotification::find()
			->where(['user_id' => Yii::$app->user->getId()])
			->andWhere(['is_read' => 0])
			->orderBy(['notification_id' => SORT_DESC])
			->limit($limit)
			->all();

		if ($data['notifications']) {
			$result = [
				'count' => count($data['notifications']),
				'content' => Yii::$app->controller->renderPartial('@common/views/common/notification_pending.tpl', $data),
			];
		}

		return $result;
	}
}