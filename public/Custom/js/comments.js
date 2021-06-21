$('document').ready(function(){
clicks();
check();
uncheck();
});
function check(){
    $(".check").hover(
        function(){
            $(this).css({
                backgroundColor: "#10AC8457",
                cursor: "pointer"
            })
        },
        function(){
            $(this).css({
                backgroundColor: "transparent"
            })
        });
}
function uncheck(){
    $(".uncheck").hover(
        function(){
            $(this).css({
                backgroundColor: "#ff6b6b57",
                cursor: "pointer"
            })
        },
        function(){
            $(this).css({
                backgroundColor: "transparent"
            })
        });
}
function clicks(){
    $(".check").click(function(){
        check = $(this).attr('target');
        //acceptedComment = $('.'+check).attr('check-target');
        refusedComment = $('.'+check).attr('uncheck-target');
        $('.'+refusedComment).hide(500);
        $('.'+check).css({
            backgroundColor: "#10AC8457"
        });
    });
    $(".uncheck").click(function(){
        check = $(this).attr('target');
        acceptedComment = $('.'+check).attr('check-target');
        
        $('.'+acceptedComment).hide(500);
        $('.'+check).css({
            backgroundColor: "#ff6b6b57"
        });
    });
}