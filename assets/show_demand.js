
const $ = require('jquery');

$(document).ready(function() {

    $('.action-open-message').click(function() {
        $('.panel-message').slideDown();
        $(this).slideUp() ;
    })
    
    $('.action-send-message').click(function() {
        $.ajax({
            type: "POST",
            url: params.url,
            data: {
                'message': $("#the-message").val(), 
            },
            dataType: "json"
        }).done( function(data) {
            if (data.success) {
                location.href = params.returnUrl ;
            } else {    
                alert(data.message) ;
            }
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown) ;
            console.log(jqXHR) ;
            alert('Unexpected error, maybe a session timeout') ;
        });
    })
    
}) ;