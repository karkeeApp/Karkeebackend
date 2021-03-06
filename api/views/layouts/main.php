<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\AccountHelper;

use api\assets\MainAsset;
use backend\assets\AppAsset;
use common\assets\HelperAsset;

MainAsset::register($this);
HelperAsset::register($this);
AppAsset::register($this);

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
<body>
<?php $this->beginBody(); ?>
<div class="wrapper">
    <div class="container-fluid">
        <?= $content ?>
    </div>
</div>

<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
