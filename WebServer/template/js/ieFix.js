(function createHTML5Elements(arrOfElements){
	for (var i = 0; i < arrOfElements.length; i++) {
    	document.createElement(arrOfElements[i]);
	}	
}(['article','aside','figcaption','figure','footer','header','hgroup','nav','section','time']));
$(window).load(function() {
	if(getCookie('isIeAlertWasClosed') === 'true') return;
	var stringIeAlert = '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" id="closeIeAlert" data-dismiss="alert" aria-hidden="true">Закрыть это уведомление   &times;</button><strong>Внимание!</strong> Ваш браузер устарел. Некоторые возможности могут быть недоступны. Пожалуста <a href="http://www.browser-update.org/ru/update.html">обновите Ваш браузер</a>. Спасибо.</div>';
	$('#ieFix').html(stringIeAlert);
	$('#closeIeAlert').click(function() {
		document.cookie = 'isIeAlertWasClosed=true';
	})
})


