jQuery(document).ready(function($){
	var t;
	$(function(){
		$('span.NameHighlights').mouseover(
			function(e){
				hideAll();
				clearTimeout(t);
				$(this).attr('class', 'NameHighlightsHover');
			}
		).mouseout(
			function(e){
				t = setTimeout(function() {
					//$(this).attr('class', 'NameHighlights');
					hideAll();
				}, 300);
			}
		);
	});

	function hideAll() {
		$('span.NameHighlightsHover').each(function(index) {
			$(this).attr('class', 'NameHighlights');
		})
	};
});

// ----------------------------------

jQuery(document).ready(function($){
	$('#CenterDIV').click(function () {
		$('#CenterDIV').css('display', 'none');
		$('.divFloat').css('display', 'none');
	});	
	$('#btClick').click(function () {
		$('#CenterDIV').css('display', 'block');
		$('.divFloat').css('display', 'block');
		hideAll();
	});
	$('#btClose').click(function () {
		$('#CenterDIV').css('display', 'none');
		$('.divFloat').css('display', 'none');
	});
});

// ----------------------------------

jQuery(document).ready(function($){
	function myFunction() {
		// Declare variables
		var input, filter, ul, li, a, i, txtValue;
		input = document.getElementById('myInput');
		filter = input.value.toUpperCase();
		ul = document.getElementById("myUL");
		li = ul.getElementsByTagName('li');

		// Loop through all list items, and hide those who don't match the search query
		for (i = 0; i < li.length; i++) {
			a = li[i].getElementsByTagName("a")[0];
			txtValue = a.textContent || a.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";
			}
		}
	}
});