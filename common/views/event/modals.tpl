@{
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    use common\helpers\Common;

    use common\models\Notification;
}

<div id="addMediaModal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-image-o"></i> Add Media</h4>
            </div>
            <div class="modal-body">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'media-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'control-label'],
                            'template' => "<div class='col-sm-6 col-md-3'>{label}</div>\n<div class=\"col-sm-6 col-md-9\">{input}{error}</div>",
                            'options' => [
                                'class' => 'row',
                            ]
                        ],
                    ]);
                }

                    @$form->field($mediaForm, 'title')->textInput(['class' => 'form-control input-sm'])
                    @$form->field($mediaForm, 'filename')->fileInput(['class' => ''])

                @{ ActiveForm::end(); }
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="saveMedia">Add</button>
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="mediaListModal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-image-o"></i> Media Library</h4>
            </div>
            <div class="modal-body">
                <div id="mediaContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="galleryModal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-image-o"></i> Gallery</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>

    </div>
</div>
