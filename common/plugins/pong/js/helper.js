
window.modAlert = function(message, callback) {
    $("#msgBox .modal-body").html(message);

    $("#msgBox").on("hidden.bs.modal", function () {
        if (typeof(callback) == 'function') {
            callback();
        }
    });

    $("#msgBox").modal('show');
};

window.modConfirm = function(message, callback_yes, callback_no, options) {
    $("#msgBoxConfirm .modal-body").html(message);

    $("#msgBoxConfirm .modal-footer button.yes")
        .unbind("click")
        .bind("click", function(e) {
            e.preventDefault();
            $("#msgBoxConfirm").modal('hide');

            if (typeof(callback_yes) == 'function') {
                callback_yes();
            }
        });

    $("#msgBoxConfirm .modal-footer button.no")
        .unbind("click")
        .bind("click", function(e) {
            e.preventDefault();
            $("#msgBoxConfirm").modal('hide');

            if (typeof(callback_no) == 'function') {
                callback_no();
            }
        });

    $("#msgBoxConfirm").modal('show');
};


window.serverProcessing = function(message) {
    if (message == null || message == undefined) {
        message = 'Processing!';
    }

    $("#msgBoxProcessing").modal('show');
   
    return {
        setMessage: function(message) {
            $("#msgBoxProcessing .modal-body").html(message);
        },
        close: function() {
            $("#msgBoxProcessing").modal('hide');
        }
    };
};

window.unloading = function() {
    $("#msgBoxProcessing").modal('hide');
};

/**
 * Highlight error using yii active form
 */
window.highlightErrors = function(errorFields) {
    $.each(errorFields, function(formName, fields) {
        $.each(fields, function( field, value ) {
            $('#' + formName).yiiActiveForm('updateAttribute', field, [value]);
        });
    });            
};

window.serverProcess = function(opts) {
    var _default = {
        action : null,
        data : null,
        form : null,
        dataType: 'json',
        callback : null,
        show_process: false,
        onError: null
    };

    var _options = $.extend({}, _default, opts);

    if (_options.dataType == "array") {
        var formData = new FormData();

        for(var i=0; i<_options.data.length; i++) {
            formData.append(_options.data[i].name, _options.data[i].value);
        }

        if (_options.form != null) {
            $("input[type=file]", _options.form).each(function() {
                $(this).prop("name")

                var inputFiles = this.files;

                if (inputFiles[0] != undefined) {
                    formData.append($(this).prop("name"), inputFiles[0]);
                }
            });
        }

        if (app.csrf_token_name != undefined) {
            formData.append(app.csrf_token_name, app.csrf_token);
        } 
        
    } else {
        var formData = _options.data;

        /* insert csrf */
        if (app.csrf_token_name != undefined) {
            
            if (typeof(formData) == "string") {
                var format = new RegExp(app.csrf_token_name, "g");
                var res = format.exec(formData);

                if (res == null) {
                    formData += "&" + app.csrf_token_name + "=" + app.csrf_token;
                }
            } else if(typeof(formData) == "object") {
                if (formData == null) {
                    formData = app.csrf_token_name + "=" + app.csrf_token;
                } else {
                    if (formData[app.csrf_token_name] == undefined) {
                        formData[app.csrf_token_name] = app.csrf_token;
                    }	
                }      						
            }
        }
    }
    
    var url = app.urlsite;

    if (/^reports\//.test(_options.action) == false) {
        url += "server/";
    }

    url += _options.action;

    var ajaxOptions = {
        url: url,
        type: 'POST',
        data: formData,
        success: function(json, textStatus, jqXHR){
            $(this).ajaxProcess = null;

            unloading();

            try{
                // if (typeof(json) == 'string'){
                //     json = $.parseJSON(json);
                // }

                /*
                 * Check if session expired
                 */
                if (json.success == false) {
                    var error = json.error;

                    if (error.indexOf("Session expired") >= 0) {
                        modAlert(error, function() {
                            window.location.href = app.urlsite;
                        });
                        return false;
                    }
                }

            } catch(e){
            }

            if (typeof(_options.callback) == "function") {
                _options.callback(json);
            }
        },
        error: function (request, status, error) {
            
            $(this).ajaxProcess = null;

            unloading();

            if( opts.onError ){
                opts.onError();
            }
        }
    };

    if (_options.dataType == "array") {
        var ajaxOptions = $.extend({}, ajaxOptions, {
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false
        });
    }


    if (_options.show_process == true) {
        if (typeof(serverProcessing) == 'function') {
            serverProcessing();

            setTimeout(function() {
                $(this).ajaxProcess = $.ajax(ajaxOptions);
            }, 500);
        } else {
            $(this).ajaxProcess = $.ajax(ajaxOptions);
        }
    } else {
        $(this).ajaxProcess = $.ajax(ajaxOptions);
    }
};

// window.serverProcess2 = function(opts) {
//     var _default = {
//         action : null,
//         data : null,
//         form : null,
//         dataType: 'json',
//         callback : null,
//         show_process: false,
//         onError: null
//     };

//     var _options = $.extend({}, _default, opts);

//     if (_options.dataType == "array") {
//         var formData = new FormData();

//         for(var i=0; i<_options.data.length; i++) {
//             formData.append(_options.data[i].name, _options.data[i].value);
//         }

//         if (_options.form != null) {
//             $("input[type=file]", _options.form).each(function() {
//                 $(this).prop("name")

//                 var inputFiles = this.files;

//                 if (inputFiles[0] != undefined) {
//                     formData.append($(this).prop("name"), inputFiles[0]);
//                 }
//             });
//         }

//         if (app.csrf_token_name != undefined) {
//             formData.append(app.csrf_token_name, app.csrf_token);
//         } 
        
//     } else {
//         var formData = _options.data;

//         /* insert csrf */
//         if (app.csrf_token_name != undefined) {
            
//             if (typeof(formData) == "string") {
//                 var format = new RegExp(app.csrf_token_name, "g");
//                 var res = format.exec(formData);

//                 if (res == null) {
//                     formData += "&" + app.csrf_token_name + "=" + app.csrf_token;
//                 }
//             } else if(typeof(formData) == "object") {
//                 if (formData == null) {
//                     formData = app.csrf_token_name + "=" + app.csrf_token;
//                 } else {
//                     if (formData[app.csrf_token_name] == undefined) {
//                         formData[app.csrf_token_name] = app.csrf_token;
//                     }	
//                 }      						
//             }
//         }
//     }
    
//     var url = app.urlsite;

//     if (/^reports\//.test(_options.action) == false) {
//         url += "server/";
//     }

//     url += _options.action;

//     var ajaxOptions = {
//         url: url,
//         type: 'POST',
//         data: formData,
//         success: function(json, textStatus, jqXHR){
//             $(this).ajaxProcess = null;

//             unloading();

//             try{
//                 // if (typeof(json) == 'string'){
//                 //     json = $.parseJSON(json);
//                 // }

//                 /*
//                  * Check if session expired
//                  */
//                 if (json.success == false) {
//                     var error = json.error;

//                     if (error.indexOf("Session expired") >= 0) {
//                         modAlert(error, function() {
//                             window.location.href = app.urlsite;
//                         });
//                         return false;
//                     }
//                 }

//             } catch(e){
//             }

//             if (typeof(_options.callback) == "function") {
//                 _options.callback(json);
//             }
//         },
//         error: function (request, status, error) {
            
//             $(this).ajaxProcess = null;

//             unloading();

//             if( opts.onError ){
//                 opts.onError();
//             }
//         }
//     };

//     if (_options.dataType == "array") {
//         var ajaxOptions = $.extend({}, ajaxOptions, {
//             cache: false,
//             dataType: 'json',
//             processData: false,
//             contentType: false
//         });
//     }


//     if (_options.show_process == true) {
//         if (typeof(serverProcessing) == 'function') {
//             serverProcessing();

//             setTimeout(function() {
//                 $(this).ajaxProcess = $.ajax(ajaxOptions);
//             }, 500);
//         } else {
//             $(this).ajaxProcess = $.ajax(ajaxOptions);
//         }
//     } else {
//         $(this).ajaxProcess = $.ajax(ajaxOptions);
//     }
// };