(function ($) {
    $(document).ready(function () {

        $('#settings_fields').tabs();

        $('#acs_max_campaigns_no').spinner({
            max: 50,
            min: 0
        });

        $('#acs_div_wrapper').click(function () {
            if ($(this).attr('checked') === 'checked') {
                $('.custom_style').css('display', 'inline');
            }
            else {
                $('.custom_style').hide();
            }
        });

        if ($('#acs_div_wrapper').attr('checked') === 'checked') {
            $('.custom_style').css('display', 'inline');
        }

        $('.field_help').tooltip({
            show: {
                effect: "slideDown",
                delay: 100
            },
            position: {
                my: "left top",
                at: "right top"
            },
            content: function () {
                var element = $( this );
                return element.attr( 'title' );
            },
            close: function ( event, ui ) {
                ui.tooltip.hover(
                    function () {
                        $( this ).stop( true ).fadeTo( 400, 1 );
                    },
                    function () {
                        $( this ).fadeOut( "400", function () {
                            $( this ).remove();
                        } );
                    } );
            }
        });

        $('.field_tip').tooltip({
            show: {
                effect: "slideDown",
                delay: 100
            },
            content: function () {
                var element = $( this );
                return element.attr( 'title' );
            },
            close: function ( event, ui ) {
                ui.tooltip.hover(
                    function () {
                        $( this ).stop( true ).fadeTo( 400, 1 );
                    },
                    function () {
                        $( this ).fadeOut( "400", function () {
                            $( this ).remove();
                        } );
                    } );
            }
        });
		
    });
})(jQuery);