$('window').ready(function() {
    $Notifications = $('li.Notif');
    $Messages = $('li.Mess');
    $Favoris = $('li.Fav');

    $Favoris.click(function(){
        $('div#Favoris').toggle(100);
        $('div#Notifications').hide();
        $('div#Messages').hide();
    });
    $Messages.click(function(){
        $('div#Messages').toggle(100);
        $('div#Favoris').hide();
        $('div#Notifications').hide();
    });
    $Notifications.click(function(){
        $('div#Notifications').toggle(100);
        $('div#Messages').hide();
        $('div#Favoris').hide();
    });
});