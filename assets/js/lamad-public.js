( function ( $ ) {
    $( document ).ready( function () {
        if ( lamadPublic.isDashboard ) {
            $( '.dashboard-controller' ).height( $( window ).height() );
        }
        var isGoogleRecaptchaValid = function () {
            if ( 'undefined' !== typeof ( grecaptcha ) ) {
                if ( !grecaptcha.getResponse() ) {
                    return false;
                }
            }
            return true;
        };

        var registerStudent = function ( form ) {
            if ( !isGoogleRecaptchaValid() ) {
                $( '.register-response' ).html( lamadPublic.grecaptchaError );
                return false;
            }
            var data = $( form ).serialize();
            $( '.register-spinner' ).removeClass( 'hidden' );
            $.post( lamadPublic.ajaxUrl, data, function ( response ) {
                $( '.register-spinner' ).addClass( 'hidden' );
                var message = '<p>' + response.data.message + '</p>';
                if ( response.success === true ) {
                    $( '#form-register' ).fadeOut();
                }
                $( '.register-response' ).html( message );
            } );
        };

        //@TODO Password validation
        if ( $( '#registration' ).length > 0 ) {
            $( '#form-register' ).validate( {
                rules: {
                    email: {
                        email: true
                    },
                    confirm_password: {
                        equalTo: '#password'
                    }
                },
                submitHandler: function ( form ) {
                    registerStudent( form );
                }
            } );
        }
    } );
} )( jQuery );