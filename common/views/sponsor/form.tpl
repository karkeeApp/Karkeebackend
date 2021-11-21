@{
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use \common\models\Sponsor;

$token = Yii::$app->security->generateRandomString();
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-file"></i> @($sponsor ? 'Update' : 'Create')</h4>
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
        'id' => 'sponsor-form',
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
        @$form->field($sponsorForm, 'id')->hiddenInput()
        <input type="text" name="token" value="@($token)" />

        <input type="hidden" name="action" value="@(($sponsorForm->id) ? 'edit' : 'add')">
    </div>

    @$form->field($sponsorForm, 'name')->textInput(['class' => 'form-control input-sm'])

    @$form->field($sponsorForm, 'category')->dropDownList([1 => 'Yes', 0 => 'No'], ['class' => 'form-control input-sm'])


    @$form->field($sponsorForm, 'image', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])

    <div class="row mb10" id="div-banner-img" style="@($sponsor ? 'display: block;' : 'display: none;')">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
            <img id="sponsor-img" src="@($sponsor ? $sponsor->imagelink() : '')" width="200" />
        </div>
    </div>

    @$form->field($sponsorForm, 'description', ['template' => '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">{label}</div><div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">{input}<div id="editor"><div id="edit-content"></div></div>{error}</div>'])->hiddenInput(['class' => 'form-control input-sm'])

    @{ ActiveForm::end(); }
</div>


<div class="text-right mt10">
    <button type="button" class="btn btn-primary btn-sm" id="saveSponsor"><i class="fa fa-save"></i> Save</button>
</div>
</div>

<script type="text/javascript">
    var editor;
    var editorElement;

    (function($) {
        var Sponsor = function() {
            var bindEvents = function() {
                $('.delete-sponsor-image').bind('click', function(e) {
                    e.preventDefault();

                    var id = $(this).attr('data-id');
                    var dis = $(this);

                    serverProcess({
                        action:'sponsor/image-delete',
                        data: 'id=' + id,
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                dis.parent().parent().remove();
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });

                /**
                 * Editor
                 */
                $("#edit-content").html($('#sponsorform-content').val());

                editor = new FroalaEditor("#edit-content", {
                    key: "@(Yii::$app->params['froala-key'])",
                    toolbarButtons: {
                        moreText: {
                            buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting'],
                            align: 'left',
                            buttonsVisible: 3
                        },
                        moreParagraph: {
                            buttons: ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote'],
                            align: 'left',
                            buttonsVisible: 3
                        },
                        moreRich: {
                            buttons: ['insertLink', 'insertImage', 'insertTable'],
                            align: 'left',
                            buttonsVisible: 3
                        },
                        moreMisc: {
                            buttons: ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help'],
                            align: 'right',
                            buttonsVisible: 2
                        }
                    },
                    imageManagerLoadURL: "@(Url::home(TRUE))sponsor/image-library",
                    imageManagerDeleteMethod: 'POST',
                    imageManagerDeleteURL: "@(Url::home(TRUE))server/sponsor/delete-media",
                    imageManagerDeleteParams: {
                        '_csrf-common':'<?=Yii::$app->request->csrfToken?>'
                    },
                    imageUploadURL: '@(Url::home(TRUE))server/sponsor/image-upload',
                    imageUploadParams: {
                        froala: 1,
                        '_csrf-common':'<?=Yii::$app->request->csrfToken?>',
                    },
                    imageUploadMethod: 'POST',
                    imageMaxSize: 20 * 1024 * 1024,
                    imageAllowedTypes: ['jpeg', 'jpg', 'png'],
                    events: {
                        'image.beforeUpload': function (images) {
                            // Return false if you want to stop the image upload.
                        },
                        'image.uploaded': function (response) {
                            // Image was uploaded to the server.
                        },
                        'image.inserted': function ($img, response) {
                            // Image was inserted in the editor.
                        },
                        'image.replaced': function ($img, response) {
                            // Image was replaced in the editor.
                        },
                        'image.error': function (error, response) {
                            // Bad link.
                            if (error.code == 1) {  }

                            // No link in upload response.
                            else if (error.code == 2) {  }

                            // Error during image upload.
                            else if (error.code == 3) {  }

                            // Parsing response failed.
                            else if (error.code == 4) { }

                            // Image too text-large.
                            else if (error.code == 5) {  }

                            // Invalid image type.
                            else if (error.code == 6) {  }

                            // Image can be uploaded only to same domain in IE 8 and IE 9.
                            else if (error.code == 7) { }

                            // Response contains the original server response to the request if available.
                        }
                    }
                });

                $('#saveMedia').bind('click', function(e) {
                    e.preventDefault();

                    var parent = $('#sponsor-filename').parent();

                    $('input[type=hidden]', parent).val($('#sponsor-filename').val());

                    var data = $('#media-form').serializeArray();

                    data.push({
                        name : 'action',
                        value : 'add'
                    });

                    serverProcess({
                        action:'sponsor/image-upload',
                        data: data,
                        dataType : 'array',
                        form : $('#media-form'),
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                $('#addMediaModal').modal('hide');
                                $('#media-form').trigger('reset');

                                modAlert(json.message);
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });

                $('#saveSponsor').bind('click', function(e) {
                    e.preventDefault();

                    $('#sponsorform-content').val(editor.html.get());

                    var parent = $('#sponsorform-image').parent();

                    $('input[type=hidden]', parent).val($('#sponsorform-image').val());

                    var data = $('#sponsor-form').serializeArray();

                    serverProcess({
                        action:'sponsor/@($sponsor ? 'update' : 'create')',
                        data: data,
                        dataType : 'array',
                        form : $('#sponsor-form'),
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message);
                                window.location.href = '@(Url::home())sponsor/';

                            @if(!$sponsorForm->id) {
                                    $('#sponsor-form').trigger('reset');
                                    editor.setData('');
                                }
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                                for(var i in json.errorFields){
                                    for(var j in json.errorFields[i]){
                                        modAlert(json.errorFields[i][j]);
                                        break;
                                    }
                                }
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });

                function readURL(input,imgpreview) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(imgpreview).attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                $('#sponsorform-image').bind('change', function(e) {
                    readURL(this,'#sponsor-img');
                    $("#div-sponsor-img").show('slow');
                });

            };



            var initRoutie = function() {

            };

            return {
                _construct : function() {
                    initRoutie();
                    bindEvents();
                }
            }
        }();


        $(document).ready(function() {
            Dropzone.autoDiscover = false;

            Sponsor._construct();
        });
    })(jQuery)


</script>