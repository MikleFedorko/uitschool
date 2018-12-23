"use strict";

$(document).ready(function () {

    $('form[name="auth"]').submit(function (e) {
        e.preventDefault();
        let errorBox = $('#errorBox');
        errorBox.html('');
        let data = $(this).serializeArray();
        let validStatus = true;

        $.each(data, function (index, field) {
            if (!field.value) {
                validStatus = false;
                errorBox.append('<span>empty ' + field.name + '</span>');
                errorBox.show();
                hideErrorBox(errorBox);
            }
        });
        if (validStatus) {

            save($(this).data('action'), $(this).serializeArray());
        }
    });

    function hideErrorBox(errorBox) {
        setTimeout(function () {
            errorBox.hide();
        }, 5000);
    }

    function save(action, data) {
        $.ajax({
            type: "post",
            url: action,
            data: data,
            dataType: 'json',
            success: function (resp) {
                if(resp.error){
                    let errorBox = $('#errorBox');
                    errorBox.html('<span>' + resp.error + '</span>');
                    errorBox.show();
                    hideErrorBox(errorBox);
                } else {
                    window.location = '/profile';
                }
            },
            error: function (jqXHR, exception) {
                console.log(jqXHR.responseJSON);
                if (jqXHR.responseJSON && jqXHR.responseJSON.hasOwnProperty('errors')) {
                    $.each(jqXHR.responseJSON.errors, function (errorKey, error) {
                        $.each(error, function (messageKey, message) {
                            $.notify({
                                message: message
                            }, {
                                type: 'danger'
                            });
                        });
                    });
                }
            }
        });
    }
});