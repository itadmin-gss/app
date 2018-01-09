jQuery(document).ready(function() {

	$("span.toggle").next().hide();

	$("span.toggle").css("cursor", "pointer");

	$("span.toggle").click(function() {
		$(this).next().toggle(600);
		$(this).toggleClass("active");
	});

});