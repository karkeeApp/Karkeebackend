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

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                
                    <?= Html::textInput('ResetPasswordForm[email]', $model->email, ['class' => 'form-control', 'autofocus' => true, 'placeholder' => 'Email', 'required' => TRUE]) ?>
                   
                

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 text-center">
                                <?= Html::submitButton('Reset', ['class' => 'btn btn-info reset-password', 'name' => 'reset-button']) ?>
                            </div>
                        
                            <div class="col-sm-6 col-sm-offset-3 text-center">
                                <?= Html::a('Already have reset code?', ['site/reset-password'], ['class' => 'small']) ?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
</div>