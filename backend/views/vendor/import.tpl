@{
	use yii\helpers\Url;
    use yii\widgets\ActiveForm;
}

@($menu)

<h4><i class="fa fa-arrow-circle-down"></i> @(Yii::t('app', 'Import Staffs'))</h4>

@{
    $form = ActiveForm::begin([
        'id' => 'import-form', 
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

@$form->field($userImportForm, 'filename', ['template' => '<div class="col-sm-3 col-md-3">{label}</div><div class="col-sm-9 col-md-9">{input}<div><a href="javascript:void(0)" id="downloadFormat"><i class="fa fa-download"></i> Download excel format</a></div>{error}</div>'])->fileInput(['class' => ''])

<div class="row">
	<div class='col-sm-3 col-md-3'></div>
	<div class="class='col-sm-9 col-md-9'">
		<a href="javascript:void(0)" id="importNow" class="btn btn-sm btn-primary">@(Yii::t('app', 'Import Now'))</a>
	</div>
</div>

@{ ActiveForm::end(); }

<hr />

<h4><i class="fa fa-history"></i> Imported Files</h4>

<div id="content"></div>

<script type="text/javascript">
(function($) {
	var Staff = function() {
		var bindEvents = function() {
			$('#downloadFormat').bind('click', function(e) {
				e.preventDefault();

				window.location.href = '@(Url::home())file/download/?name=staff-import-format.xlsx';
			});

			$('#importNow').bind('click', function(e) {
				e.preventDefault();

				var parent = $('#userimportform-filename').parent();

                $('input[type=hidden]', parent).val($('#userimportform-filename').val());

                var data = $('#import-form').serializeArray();

                data.push({
                    name : 'action',
                    value : 'add'
                });

				serverProcess({
					action : 'staff/import',
					data : data,
					dataType : 'array',
					form : $('#import-form'),
					show_process : true,
					callback : function(json) {
						if (json.success) {
							window.location.reload();
						} else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
						} else {
							modAlert(json.error);
						}
					}
				});
			});
		};

		var loadList = function(page, filter) {
	        var data = '';

	        if (typeof(page) == 'undefined') page = 1;
	        data += 'page=' + page;

	        @if(isset($account)) {
		        data += '&account_id=@($account->account_id)';
	        }

	        if (typeof(filter) != 'undefined') {
	            try{
	                var f = $.parseJSON(filter);

	                $('#keyword').val(f.keyword);
	                $('#status').val(f.status);

	                data += '&keyword=' + f.keyword;
	                data += '&status=' + f.status;
	                data += '&filter=' + filter;

	            }catch(e) {

	            }
	        }

	        serverProcess({
				action : 'staff/importlist',
				data : data,
				show_process : true,
				callback : function(json) {
					if (json.success) {
						$('#content').html(json.content);
					} else {
						modAlert(json.error);
					}
				}
			});
	    };

		var initRoutie = function() {
			routie({
				'list/filter/:filter/page/:page' : function(filter, page) {
					loadList(page, filter);
				},
				'list/filter/:filter' : function(filter) {
					loadList(1, filter);
				},
				'list/page/:page' : function(page) {
		            loadList(page);
		        },
				'*' : function() {
					loadList(1);
				}
			});
		};

		return {
			_construct : function() {
				initRoutie();
				bindEvents();
			}
		};
	}();

	$(document).ready(function() {
		Staff._construct();
	});
})(jQuery);
</script>