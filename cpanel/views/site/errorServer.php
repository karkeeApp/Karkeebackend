<?php
	use yii\helpers\Html;
	
	$error = [
		'title' => Html::encode($this->title),
		'error' => nl2br(Html::encode($message)),
	];

	echo json_encode($error);
?>