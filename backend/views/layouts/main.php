<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\AccountHelper;

use backend\assets\MainAsset;
use backend\assets\AppAsset;
use common\assets\HelperAsset;
use common\assets\GenetellaAsset;
use common\controllers\Controller;
use common\forms\AccountUserPasswordForm;
use yii\bootstrap\ActiveForm;

//GenetellaAsset::register($this);
MainAsset::register($this);
HelperAsset::register($this);
AppAsset::register($this);
$bundle = yiister\gentelella\assets\Asset::register($this);


$notificationPending = AccountHelper::pendingNotifications();

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Url::home() ?>favicon.ico" />
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        var app = {
            urlsite : '<?=Url::home()?>'
        };
    </script>
</head>
<body class="nav-md">
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-user"></i> <span><?php echo Yii::$app->view->title ?></span></a>
                </div>
                <div class="clearfix"></div>

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>General</h3>
                        <?php
                            if (!Yii::$app->user->isGuest) {
                                $user = Yii::$app->user->identity;

                                if (Controller::hasPermission(['member_read', 'member_write'])){
                                    $items['items'][] = [
                                        "label" => "Members", "icon" => "users", "url" => "javascript:void(0)",
                                        "items" => [
                                            ["label" => "All", "url" => Url::home() . 'member', "icon" => ""],                                                                        
                                            ["label" => "Existing", "url" => Url::home() . 'member/existing', "icon" => ""],
                                            ["label" => "Renewal", "url" => Url::home() . 'member/renewal', "icon" => ""],
                                            ["label" => "Pending Approval", "url" => Url::home() . 'member/pendingapproval', "icon" => ""],
                                            ["label" => "Deleted", "url" => Url::home() . 'member/deleted', "icon" => ""],
                                        ]
                                    ];
                                }

                                if (Controller::hasPermission(['vendor_read', 'vendor_write'])){
                                    $items["items"][] = ["label" => "Vendors", "url" => ["/vendor"], "icon" => "users"];
                                }

                                if (Controller::hasPermission(['listing_read', 'listing_write'])){
                                    $items['items'][] = ["label" => "Listing", "url" => ["/listing"], "icon" => "cogs"];
                                }

                                // if (Controller::hasPermission(['service_read', 'service_write'])){
                                //     $items['items'][] = ["label" => "Services", "url" => ["/item"], "icon" => "cogs"];
                                // }

                                if (Controller::hasPermission(['accountadmin_read', 'accountadmin_write'])){
                                    $items['items'][] = [
                                        "label" => "Account Admin", 
                                        "url" => ["/accountadmin"], 
                                        "icon" => "users",
                                    ];
                                }

                                if (Controller::hasPermission(['news_read', 'news_write'])){
                                    $items['items'][] = ["label" => "News", "url" => ["/news"], "icon" => "newspaper-o"];
                                }
                                if (Controller::hasPermission(['event_read', 'event_write'])){
                                    $items['items'][] = ["label" => "Events", "url" => ["/event"], "icon" => "newspaper-o",];
                                }
                                if (Controller::hasPermission(['banner_read', 'banner_write'])){
                                    $items['items'][] = ["label" => "Banners", "url" => ["/banner"], "icon" => "star"];
                                }

                                if (Controller::hasPermission(['support_read', 'support_write'])){
                                    $items['items'][] = [
                                        "label" => "Support", "icon" => "chain", "url" => "javascript:void(0)",
                                        "items" => [
                                            ["label" => "Requests", "url" => ["/support"], "icon" => ""],
                                            ["label" => "Replies", "url" => ["/supportreply"], "icon" => ""],
                                        ]
                                    ];
                                }
                                if (Controller::hasPermission(['settings_read', 'settings_write'])){
                                    $items['items'][] = ["label" => "Settings", "url" => ["/settings"], "icon" => "cog"];
                                }
                                echo \yiister\gentelella\widgets\Menu::widget($items);
                            }
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo Yii::$app->user->identity->email; ?>
                                <i class=" fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li id="changePasswordModal"><a href="javascript:void(0);"><i class="fa fa-user"></i> <?php echo Yii::t('app', 'Change Password'); ?></a></li>
                                <li style="padding-left: 20px">
                                    <?php
                                    echo Html::beginForm(['/site/logout'], 'post');
                                    echo Html::submitButton(
                                        '<i class="fa fa-sign-out"></i> Logout',
                                        [
                                            'class' => 'btn btn-link logout',
                                            'style' => 'padding: 0'
                                        ]
                                    );
                                    echo Html::endForm();
                                    ?>
                                </li>
                            </ul>
                        </li>

                        <?php if($notificationPending['count']) : ?>
                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="badge bg-green"><?php echo $notificationPending['count'] ?></span>
                                </a>

                                <?php echo $notificationPending['content'] ?>
                            </li>
                        <?php endif; ?>                       

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" style="padding-bottom: 20px;">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left"><f
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?= $content ?>
        </div>
        <!-- /page content -->
    </div>

</div>

<?php echo $this->render('@common/views/common/modals.tpl'); ?>

<?php
    if (!Yii::$app->user->isGuest) {
?>
<div id="chformchangepass" style="display: none;">
<?php

$accountUserPasswordForm = new AccountUserPasswordForm();
$accountUserPasswordForm->account_id = $user->account_id;

$form = ActiveForm::begin([
    'id' => 'password-form', 
    'enableClientScript' => true,
    'fieldConfig' => [
        'labelOptions' => ['class' => 'col-sm-2 control-label'],
        'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
    ],
]);
?>
<div class="hide"> 
    <input type="hidden" name="action" value="admin_add">
    <?= $form->field($accountUserPasswordForm, 'account_id')->textInput() ?>
</div>
<?= $form->field($accountUserPasswordForm, 'new')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off']) ?>

<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
     $(function () {
        $('#changePasswordModal').on('click', function(e) {   
                $("#msgBoxConfirm .modal-title").html("Change Password:");
                var msgmbCP =   $('#chformchangepass').html();
                modConfirm(msgmbCP, 
                    function() {
                        serverProcess({
                            action:'accountadmin/updatepassword',
                            data: $('#password-form').serialize() + '&user_id=@($user->user_id)',
                            show_process:true,
                            callback:function(json){
                                if(json.success){
                                    modAlert(json.message);
                                    $('#password-form').trigger('reset');
                                } else if(typeof(json.errorFields) == 'object'){
                                    window.highlightErrors(json.errorFields);
                                }else{
                                    modAlert(json.error);
                                }
                            }
                        });
                    }
                );

            });

                
            
        });
</script>
<?php
}
?>
<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
