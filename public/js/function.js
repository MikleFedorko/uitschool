"use strict";

$(document).ready(function () {

    $('form[name="auth"]').submit(function (event) {
        event.preventDefault();
        let errorBox = $('#errorBox');
        let data = $(this).serializeArray();
        let validStatus = true;

        errorBox.html('');
        $.each(data, function (index, field) {
            if (!field.value) {
                validStatus = false;
                errorBox.append('<span>empty ' + field.name + '</span>');
                errorBox.show();
                hideErrorBox(errorBox);
            }
        });

        if (validStatus) {
            save(errorBox, $(this).data('action'), data);
        }
    });

    function hideErrorBox(errorBox) {
        setTimeout(function () {
            errorBox.hide();
        }, 5000);
    }

    function save(errorBox, action, data) {
        $.ajax({
            type: "post",
            url: action,
            data: data,
            dataType: 'json',
            success: function (resp) {
                if(resp.error){
                    errorBox.html('<span>' + resp.error + '</span>');
                    errorBox.show();
                    hideErrorBox(errorBox);
                } else {
                    window.location = resp.path;
                }
            },
            error: function (jqXHR, exception) {
                if (jqXHR.responseJSON && jqXHR.responseJSON.hasOwnProperty('errors')) {
                    $.each(jqXHR.responseJSON.errors, function (errorKey, error) {
                        $.each(error, function (messageKey, message) {
                            console.log(exception, message);
                        });
                    });
                }
            }
        });
    }
});