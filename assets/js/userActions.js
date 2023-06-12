function subscribe(userTo, userFrom, button){
    if(userTo == userFrom){
        alert("Вы не можете подписаться на себя");
        return;
    }

    $.post("ajax/subscribe.php", {userTo: userTo, userFrom: userFrom})
    .done(function(count){
        if(count != null){
        	$(button).toggleClass("subscribe unsubscribe");

            var buttonText = $(button).hasClass("subscribe") ? "ПОДПИСАТЬСЯ" : "ВЫ ПОДПИСАНЫ";
        	$(button).text(buttonText + " " + count);
        }else{
        	alert("Что-то пошло не так");
        }
    });
}