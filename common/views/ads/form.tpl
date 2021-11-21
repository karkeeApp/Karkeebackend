@{
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use \common\models\Ads;

$token = Yii::$app->security->generateRandomString();
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-file"></i> @($ads ? 'Update' : 'Create')</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">

    </div>
</div>

<br />

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
</ul>

<div class="tab-content">
    <div id="details" class="tab-pane active">
        @{
        $form = ActiveForm::begin([
        'id' => 'ads-form',
        'enableClientScript' => true,
        'fieldConfig' => [
        'labelOptions' => ['class' => 'control-label'],
        'template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-9\">{input}{error}</div>",
    'options' => [
    'class' => 'row mb10',
    ]
    ],
    ]);
    }

    <div class="hide">
        @$form->field($adsForm, 'id')->hiddenInput()
        <input type="hidden" name="action" value="@(($adsForm->id) ? 'edit' : 'add')">
    </div>


    @$form->field($adsForm, 'name')->textInput(['class' => 'form-control input-sm'])

    @$form->field($adsForm, 'filename', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])


    <div class="row mb10" id="div-banner-img" style="@($ads ? 'display: block;' : 'display: none;')">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
            <img id="banner-img" src="@($ads ? $ads->filelink() : '')" width="200" />
        </div>
    </div>

    @$form->field($adsForm, 'description')->textInput(['class' => 'form-control input-sm'])
    @$form->field($adsForm, 'link')->textInput(['class' => 'form-control input-sm'])


    @{ ActiveForm::end(); }
</div>

<div class="text-right mt10">
    <button type="button" class="btn btn-primary btn-sm" id="saveAds"><i class="fa fa-save"></i> Save</button>
</div>
</div>

<script type="text/javascript">
    (function($) {
        $('#saveAds')
            .bind('click', function(e) {
                e.preventDefault();

                var data = $('#ads-form').serializeArray();

                serverProcess({
                    action:'ads/@($ads ? 'update' : 'create')',
                    data: data,
                    dataType : 'array',
                    form : $('#ads-form'),
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                            window.location.href = '@(Url::Home())ads';
                        @if(!isset($ads)) {
                                    //window.location.href = '@(Url::Home())ads';
                            }
                        } else if(typeof(json.errorFields) == 'object'){
                           window.highlightErrors(json.errorFields);
                        }else{
                             modAlert(json.error);
                        }
                    }
                });
            });
    })(jQuery);

</script>