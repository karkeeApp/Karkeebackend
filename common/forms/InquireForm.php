<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class InquireForm extends Model
{
	public $message;

	public function rules()
    {
        return [
			[['message'], 'required'],
		];
	}
}