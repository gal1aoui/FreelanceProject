$(document).ready(function(){
    var deg = -90;
    var px = 500;
    $('.msg-toggler').on('click',function(){    
        $('#msgarrow').css({
            transform: "rotate("+ deg +"deg)",
            });
            deg *= -1;
        $('#Followers').css({
            height: px+"px"
        });
        if(px > 50){
        px /= 10 ;
        }else{
            px *= 10;
        }
    });
});
