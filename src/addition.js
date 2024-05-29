

$(document).ready(function () {

    if (window.innerWidth < 750) {
        $('#toggle-nav').prop('checked', true)
    } else {
        $('#toggle-nav').prop('checked', false)
    }

    var lastScrollTop = 0;

    $(window).on('scroll', function () {
        var nav = $('#top-nav');
        var isSticky = nav.hasClass('sticky');
        var scrollTop = $(this).scrollTop();

        if (scrollTop > lastScrollTop) {
            // Scrolling down
            nav.css({
                'box-shadow': '2px 0 5px rgba(0,0,0,.6)'
            });
        } else {
            // Scrolling up or not scrolling
            nav.css({
                'box-shadow': ''
            });
        }

        lastScrollTop = scrollTop;
    });

    

    $(window).on('resize', function(){
        if (window.innerWidth < 750) {
            $('#toggle-nav').prop('checked', true)
        } else {
            $('#toggle-nav').prop('checked', false)
        }

        let state = $('#toggle-nav').prop('checked');


        if(state){
            $('.to-hide').hide('200');
            $('#burger').addClass('rotate');
        }else{
            $('.to-hide').show('200');
            $('#burger').removeClass('rotate');

        }
    });
    
    
    

    let state = $('#toggle-nav').prop('checked');


    if(state){
        $('.to-hide').hide('200');
        $('#burger').addClass('rotate');
    }else{
        $('.to-hide').show('200');
        $('#burger').removeClass('rotate');

    }
    
    $('#toggle-nav').on('click', function(){
        let state = $(this).prop('checked');
        if(state){
            $('.to-hide').hide('200');
            $('#burger').addClass('rotate');
        }else{
            $('.to-hide').show('200');
            $('#burger').removeClass('rotate');

        }
    });
    $(".dt-input").attr("placeholder", "Search...");
});