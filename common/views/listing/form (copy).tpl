@{
    use yii\helpers\Url;    

    use yii\widgets\ActiveForm;
    use \common\models\Item;
}

@($menu)

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-cog"></i> Edit Service</h4>
    </div>
    <div class="col-lg-6 text-right">
    </div>
</div>

<br />

@{
    $form = ActiveForm::begin([
        'id' => 'item-form', 
        'enableClientScript' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
            'template' => "<div class='col-sm-6 col-md-2'>{label}</div>\n<div class=\"col-sm-6 col-md-10\">{input}{error}</div>",
            'options' => [
                'class' => 'row',
            ]
        ],
    ]);
}

    <div class="hide">
        @$form->field($listingForm, 'listing_id')->hiddenInput()

        <input type="hidden" name="action" value="@(($listingForm->listing_id) ? 'edit' : 'add')">
    </div>

    @$form->field($listingForm, 'title')->textInput(['class' => 'form-control input-sm'])
    @$form->field($listingForm, 'content')->textArea(['class' => 'form-control input-sm'])

    <div class="text-right mt10">
        <button type="button" class="btn btn-primary btn-sm" id="saveItem"><i class="fa fa-save"></i> Save</button>
    </div>
@{ ActiveForm::end(); }


<script type="text/javascript">
(function($) {
    var Item = function() {
        var bindEvents = function() {
            $('#saveItem').bind('click', function(e) {
                e.preventDefault();

                var data = $('#item-form').serializeArray();

                serverProcess({
                    action:'listing/update/@($listing->listing_id)',
                    data: data,
                    show_process:true,
                    callback:function(json){                            
                        if(json.success){
                            modAlert(json.message);
                        } else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });
        };
        
        return {
            _construct : function() {
                bindEvents();
            }
        }       
    }();

    $(document).ready(function() {
        Item._construct();
    });
})(jQuery)
</script>