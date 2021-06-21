$(window).on('load',function(){
    var deg = -90;
$('.navbar-toggler').on('click',function(){  
    $('#arrow').css({
    transform: "rotate("+ deg +"deg)",
    });
    deg *= -1;
});
if($(window).width()> 991){
    $('.navbar-collapse').css({
        height: "48px"
        });
}

});