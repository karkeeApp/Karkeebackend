@{
    use yii\helpers\Url;    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use \common\models\Item;
}

@($menu)

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

<div class="col-lg-6">
    <h4><i class="fa fa-cog"></i> Edit Service</h4>
</div>
<div class="col-lg-6 text-right">
</div>

<div id="item-details" class="container">

    <div class="hide">
        @$form->field($itemForm, 'item_id')->hiddenInput()

        <input type="hidden" name="action" value="@(($itemForm->item_id) ? 'edit' : 'add')">
    </div>
    @$form->field($itemForm, 'title')->textInput(['class' => 'form-control input-sm'])
    @$form->field($itemForm, 'amount')->textInput(['class' => 'form-control input-sm'])
    @$form->field($itemForm, 'limit')->textInput(['class' => 'form-control input-sm'])
    @$form->field($itemForm, 'content')->textArea(['class' => 'form-control input-sm'])

    <div class="text-right mt10">
        <button type="button" class="btn btn-primary btn-sm" id="saveItem"><i class="fa fa-save"></i> Save</button>
    </div>
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
                    action:'item/update/@($item->item_id)',
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