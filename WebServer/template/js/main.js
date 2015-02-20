$(document).ready(function(){
   $('#imgLoad').hide(); 
});
var num = 0,
    isItLast = true;
$(function() {
   $('#load-more').click(function(){ 
   $('#imgLoad').show(); 
   		$.get( '/people.php', {'num' : num++}, function(e) {
   			if (isItLast) {
   				$('#imgLoad').hide();
          $('#nextPeople').append(e).hide().fadeIn(700);
       		    $('#load-more').popover('show');
       		    $('#load-more').attr('disabled', true)
        		setInterval(function() { 
        			$('#load-more').slideUp(200);
        			$('#load-more').popover('destroy');
        		}, 4500);	
        		return;
   			}
   			$('#nextPeople').append(e).hide().fadeIn(700);
   			$('#imgLoad').hide();
   		 });
    });
});
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}