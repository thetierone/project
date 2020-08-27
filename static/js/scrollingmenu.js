 if ($(window).width() > 992) {
    $(window).scroll(function(){
        if ($(this).scrollTop() > 40) {
            $('#navbar_top').addClass("fixed-top");
            $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
        }else{
            $('#navbar_top').removeClass("fixed-top");
            $('body').css('padding-top', '0');
        }
    });
}
