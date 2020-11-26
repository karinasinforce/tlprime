// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var $header = $('#headerBar');
var navbarHeight = $header.outerHeight(true);

var __mainHeaderHeigt = $('.main-header').height();
$('.main-header').height(__mainHeaderHeigt);

$(window).resize(function(event) {
    setInterval(function() {
        // $('.main-header').removeAttr('style');
        $header = $('#headerBar');
        navbarHeight = $header.outerHeight(true);
        __mainHeaderHeigt = $('.main-header').height();
        
        $('.main-header').height(__mainHeaderHeigt);

    }, 250);
    
});




$(window).scroll(function(event){
    var _ScrollTop = $(window).scrollTop();
    if ( _ScrollTop > navbarHeight ) {
        didScroll = true;
    } else {
        didScroll = false;
        $header.removeClass('nav-up nav-down');
    }
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $header.removeClass('nav-down').addClass('nav-up').css('top', - (navbarHeight + 20) );
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $header.removeClass('nav-up').addClass('nav-down').css('top', 0);
        }
    }
    
    lastScrollTop = st;
}