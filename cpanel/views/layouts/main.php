<?php

use machour\yii2\notifications\widgets\NotificationsWidget;
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
                    <a href="/" class="site_title"><i class="fa fa-cogs"></i> <span><?php echo Yii::$app->view->title ?></span></a>
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

                                $items["items"] = [
                                    ["label" => "Home", "url" => ["/"], "icon" => "home"],
                                    ["label" => "Ads", "url" => ["/ads"], "icon" => "flag-checkered"],
                                    ["label" => "Payment", "url" => ["/userpayment"], "icon" => "money"],
                                    ["label" => "Clubs / Companies", "url" => ["/account"], "icon" => "users"],
                                    ["label" => "Members", "url" => ["/member"], "icon" => "users"],
                                    ["label" => "Vendors", "url" => ["/vendor"], "icon" => "users"],
                                    //["label" => "Services", "url" => ["/item"], "icon" => "gift"],
                                    //["label" => "Redemption", "url" => ["/redeem"], "icon" => "star"],
                                    ["label" => "Sponsor", "url" => ["/sponsor"], "icon" => "diamond"],
                                    // ["label" => "News", "url" => ["/news"], "icon" => "newspaper-o"],
                                    // ["label" => "Events", "url" => ["/news"], "icon" => "newspaper-o"],
                                    // ["label" => "Banners", "url" => ["/news"], "icon" => "newspaper-o"],
                                    // ["label" => "Admin", "url" => ["/admin"], "icon" => "users"],
                                ];

                                if (Controller::hasPermission(['listing_read', 'listing_write'])){
                                    $items['items'][] = ["label" => "Listing", "url" => ["/listing"], "icon" => "cogs"];
                                }
                                
                                if (Controller::hasPermission(['news_read', 'news_write'])){
                                    $items['items'][] = ["label" => "News", "url" => ["/news"], "icon" => "newspaper-o"];
                                }
                                if (Controller::hasPermission(['event_read', 'event_write'])){
                                    $items['items'][] = ["label" => "Events", "url" => ["/event"], "icon" => "newspaper-o"];
                                }
                                if (Controller::hasPermission(['banner_read', 'banner_write'])){
                                    $items['items'][] = ["label" => "Banners", "url" => ["/banner"], "icon" => "star"];
                                }
                                if (Controller::hasPermission(['club_read', 'club_write'])){
                                    $items['items'][] = [
                                        "label" => "Club App Request",
                                        "url" => ["/club"],
                                        "icon" => "users",
                                    ];
                                }
                                if (Controller::hasPermission(['accountadmin_read', 'accountadmin_write'])){
                                    $items['items'][] = [
                                        "label" => "Admin", 
                                        "url" => ["/admin"], 
                                        "icon" => "users",
                                    ];
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
                                <?php echo Yii::$app->user->identity->username; ?>
                                <i class=" fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <!-- <li><a href="<?= Url::Home() ?>settings"><i class="fa fa-cog"></i> Settings</a></li> -->
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

<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
