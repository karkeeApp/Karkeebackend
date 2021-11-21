<?php

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use common\models\User;

?>

<div class="container">    
    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="text-center">
                <h1><i class="fa fa-user-circle"></i></h1>
            </div>

            <div class="panel-heading">
                <div class="panel-title text-center"><?php echo Yii::$app->view->title ?></div>
            </div>     

            <div style="padding-top:30px" class="panel-body" >
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal']]); ?>
                    <div class="hide">
                        <?=$form->field($model, 'account_id')->textInput()?>
                    </div>
                    <div class="row">
                        <?= common\widgets\Alert::widget() ?>
                    </div>
                    <?php if ($model->getErrors()) : ?>
                    <div id="login-alert" class="alert alert-danger col-sm-12">
                        <?php
                            foreach($model->getErrors() as $row) {
                                echo "<div>{$row[0]}</div>";
                            }
                        ?>                        
                    </div>
                    <?php endif; ?>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <?= Html::textInput('ResetPasswordForm[email]', $model->email, ['class' => 'form-control', 'autofocus' => true, 'placeholder' => 'Email Address', 'required' => FALSE]) ?>
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls">
                            <?= Html::submitButton('Reset', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3 text-center">
                            <?= Html::a('Already have reset code?', ['site/reset-password','account_id' => $model->account_id], ['class' => 'small']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>                     
        </div>  
    </div>
</div>
