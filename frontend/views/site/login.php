<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;

?>

<div class="container">
    <div class="card card-container">
        <div class="text-center">
            <h1><i class="fa fa-user-circle"></i></h1>
        </div>

        <p id="profile-name" class="profile-name-card"><?php echo Yii::$app->view->title ?></p><br />

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-signin']]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username', 'required' => TRUE])->label(FALSE) ?>
            
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'required' => TRUE])->label(FALSE) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

            <?php ActiveForm::end(); ?>
    </div>
</div>
