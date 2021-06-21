var msgScroll = document.getElementById('Messages');
var area = document.getElementById('msgarrow');
var status = 1;

area.addEventListener("click", function(){
    msgScroll.style.overflowY = "scroll";
    status *= -1;
    if( status > 0 ){
        msgScroll.style.overflowY = "hidden";
    }
});