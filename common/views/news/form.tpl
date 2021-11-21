@{
    use yii\helpers\Url;    

    use yii\widgets\ActiveForm;
    use \common\models\News;

    $token = Yii::$app->security->generateRandomString();
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-file"></i> @($news ? 'Update' : 'Create')</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <!-- <a href="javascript:void(0)" onclick="$('#addMediaModal').modal();" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add Media Library</a> -->
    </div>
</div>

<br />

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
    <li><a data-toggle="tab" href="#gallery"><i class="fa fa-image"></i> Gallery</a></li>
</ul>

<div class="tab-content">
    <div id="details" class="tab-pane active">
        @{
            $form = ActiveForm::begin([
                'id' => 'news-form', 
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
                @$form->field($newsForm, 'news_id')->hiddenInput()
                <input type="text" name="token" value="@($token)" />

                <input type="hidden" name="action" value="@(($newsForm->news_id) ? 'edit' : 'add')">
            </div>

            @$form->field($newsForm, 'title')->textInput(['class' => 'form-control input-sm'])
            @$form->field($newsForm, 'summary')->textInput(['class' => 'form-control input-sm'])
            @$form->field($newsForm, 'order')->textInput(['class' => 'form-control input-sm'])
            
            <div class="row mb10">
                <div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>
                    Categories
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    @$form->field($newsForm, 'is_guest', ['template' => "<div>{input} {label}</div>", 'options' => ['class' => '', 'style'=>'display: inline-block; margin-right: 10px;']])->checkbox()
                    @$form->field($newsForm, 'is_news', ['template' => "<div>{input} {label}</div>", 'options' => ['class' => '', 'style'=>'display: inline-block; margin-right: 10px;']])->checkbox()
                </div>
            </div>

            @$form->field($newsForm, 'is_public')->dropDownList([1 => 'Yes', 0 => 'No'], ['class' => 'form-control input-sm'])

            @$form->field($newsForm, 'image', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])


            <div class="row mb10" id="div-news-img" style="@($news ? 'display: block;' : 'display: none;')">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
                    <img id="news-img" src="@($news ? $news->imagelink() : '')" width="200" />
                </div>
            </div>

            @$form->field($newsForm, 'content', ['template' => '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">{label}</div><div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">{input}<div id="editor"><div id="edit-content"></div></div>{error}</div>'])->hiddenInput(['class' => 'form-control input-sm'])

        @{ ActiveForm::end(); }
    </div>

    <div id="gallery" class="tab-pane">
        @{
            $dropzoneUrl = ($news) ? Url::home() . 'server/news/gallery-upload?id=' . $news->news_id : Url::home() . 'server/news/gallery-upload-by-token?token=' . $token;
        }

        <form id="news-gallery-container" action="@($dropzoneUrl)" class="dropzone">
            @if($news AND $news->galleries){
                @foreach($news->galleries as $gallery){
                    <div class="dz-preview dz-processing dz-success dz-complete dz-image-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail src="@($gallery->filelink())" width="120" />
                        </div>
                        <div class="text-center">
                            <a href="javascript:void(0)" data-model="news" data-id="@($gallery->gallery_id)" class="delete-gallery text-danger"><i class="fa fa-remove"></i> Delete</a>
                        </div>
                    </div>
                }
            }
        </form>
    </div>

    <div class="text-right mt10">
        <button type="button" class="btn btn-primary btn-sm" id="saveNews"><i class="fa fa-save"></i> Save</button>
    </div>
</div>

<script type="text/javascript">
    var editor;
    var editorElement;

(function($) {

        var Media = function() {       

            var bindEvents = function() {
                
                
               
                
                $('.delete-gallery').bind('click', function(e) {
                    e.preventDefault();

                    var id = $(this).attr('data-id');
                    var dis = $(this);

                    serverProcess({
                        action:'news/gallery-delete',
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
                $("#edit-content").html($('#newsform-content').val());
                
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
                    imageManagerLoadURL: "@(Url::home(TRUE))news/media-library",
                    imageManagerDeleteMethod: 'POST',
                    imageManagerDeleteURL: "@(Url::home(TRUE))server/news/delete-media",
                    imageManagerDeleteParams: {
                        '_csrf-common':'<?=Yii::$app->request->csrfToken?>'
                    },
                    imageUploadURL: '@(Url::home(TRUE))server/news/mediaupload',
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

                $('#saveNews').bind('click', function(e) {
                    $('#news-form').trigger('submit');
                });
                $('#news-form')
                    .bind('submit', function(e){ e.preventDefault();
                        e.preventDefault();
                        $('#newsform-content').val(editor.html.get());
                    })
                    .bind('beforeSubmit', function(e) {
                    e.preventDefault();

                    var parent = $('#newsform-image').parent();

                    $('input[type=hidden]', parent).val($('#newsform-image').val());

                    var data = $('#news-form').serializeArray();

                    serverProcess({
                        action:'news/update',
                        data: data,
                        dataType : 'array',
                        form : $('#news-form'),
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message);
                                window.location.href = '@(Url::home())news/';

                                @if(!$newsForm->news_id) {
                                    $('#news-form').trigger('reset');
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
                $('#newsform-image').bind('change', function(e) {
                    readURL(this,'#news-img');
                    $("#div-news-img").show('slow');
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

            Media._construct();

        });
    })(jQuery)

    
</script>