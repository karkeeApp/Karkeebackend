<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;

?>

<div class="container">
    <div class="login-container">
            <div id="output"></div>
            <div class="avatar-temp">
                <h1><i class="fa fa-user"></i></h1>
                <h3><?php echo Yii::$app->view->title ?></h3>
            </div>

            <div class="form-box">
                <div class="row">
                <?= common\widgets\Alert::widget() ?>
                </div>
                <?php if ($model->getErrors()) : ?>
                    <div class="row">
                        <div class="alert alert-danger col-sm-12">
                            <?php
                                foreach($model->getErrors() as $row) {
                                    echo "<div>{$row[0]}</div>";
                                }
                            ?>                        
                        </div>
                    </div>
                <?php endif; ?>

                <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>
                    <div class="form-group">
                    <div class="row">
                    <?= Html::textInput('ResetPasswordForm[reset_code]', $model->reset_code, ['class' => 'form-control', 'autofocus' => true, 'placeholder' => 'Reset Code', 'required' => TRUE]) ?>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                    <?= Html::textInput('ResetPasswordForm[email]', $model->email, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => TRUE]) ?>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                    <?= Html::passwordInput('ResetPasswordForm[password]', $model->password, ['class' => 'form-control', 'placeholder' => 'Password', 'required' => TRUE]) ?>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                    <?= Html::passwordInput('ResetPasswordForm[confirm_password]', $model->confirm_password, ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'required' => TRUE]) ?>
                    </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 text-center">
                                <?= Html::submitButton('Update', ['class' => 'btn btn-info btn-block login', 'name' => 'login-button']) ?>
                            </div>
                            <div class="col-sm-6 col-sm-offset-3 text-center">
                                <?= Html::a('Login?', ['site/login'], ['class' => 'small']) ?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
</div>