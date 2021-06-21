$('document').ready(function(){

$('#Previous').hide();

var n = $('.cardd').length;
var nbr = 3;

$('#Next').click(function(){
    nbr++;
    if(nbr < 3){
        nbr = 4;
    }else {
        $('#Previous').show();
    }  
$(this).attr('href','#next'+nbr);
if(nbr > n){
    $('#Previous').hide();
    $(this).attr('href','#next1');
    nbr = 1;
}
});

$('#Previous').click(function(){  
    if(nbr % 2 == 0){
    nbr -= 3;
    }else{
        nbr -= 4;
    }
    if (nbr < 0){
        nbr = 1;
    }
    if(nbr < 2){
        $('#Previous').hide();
    }
$(this).attr('href','#next'+nbr);
});

});