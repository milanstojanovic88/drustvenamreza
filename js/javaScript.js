$(document).ready(function(){
	
	var child = $('.main_content').height();
	
	$('.content').height(child+20);
});

/*function searchq(){
	var seachInput = $("input[name='search']").val();
	
	$.post("search.php", {search: searchTxt}, function(output){
		
	});
}*/