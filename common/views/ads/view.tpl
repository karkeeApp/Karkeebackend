@{
use yii\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Common;
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-info"></i> @($ads->name)</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <a href="@(Url::home())ads/edit/@($ads->id)" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit</a>
    </div>
</div>

<br />

<ul class="nav nav-tabs mb10">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
</ul>

<div class="tab-content">
    <div id="details" class="tab-pane active">
        <div class="row mb10">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
                <div class="text-left">
                    @if ($ads->image) {
                    <img class="img-responsive" src="@($ads->filelink())" width="200"/>
                    }
                </div>
            </div>
        </div>

        <div class="row mb10">
            @Html::activeLabel($ads, 'name', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($ads, 'name', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>

        <div class="row mb10">
            @Html::activeLabel($ads, 'description', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($ads, 'description', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>



        <div class="row mb10">
            @Html::activeLabel($ads, 'user_id', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($ads, 'user_id', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>


        <div class="row mb10">
            @Html::activeLabel($ads, 'is_bottom', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($ads, 'is_bottom', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9', 'value' => $ads->is_bottom ? 'Up' : 'Bottom' ])
        </div>


        @{
        $form = ActiveForm::begin([
        'id' => 'user-form',
        'enableClientScript' => true,
        'fieldConfig' => [
        'labelOptions' => ['class' => 'control-label'],
        'template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-9\">{input}{error}</div>",
    'options' => [
    'class' => 'row',
    ]
    ],
    ]);
    }

        @$form->field($ads, 'link')->textArea(['class' => 'form-control input-sm', 'disabled' => true])

    @{ ActiveForm::end(); }






    </div>

</div>


