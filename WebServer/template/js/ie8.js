(function createHTML5Elements(arrOfElements){
	for (var i = 0; i < arrOfElements.length; i++) {
    	document.createElement(arrOfElements[i]);
	}	
}(['article','aside','figcaption','figure','footer','header','hgroup','nav','section','time']));