// js for front end of ProGo Themes RealEstate sites

function propJS($) {
	$('#main .tab').hide();
	$('#tabs a').click(function() {
		$(this).parent().addClass('on').siblings('.on').removeClass();
		$($(this).attr('href')).show().siblings('.tab:visible').hide();
		return false;
	}).eq(0).click();

	var whash = '' + window.location.hash;
	
	$('#mtabs a').each(function(i) {
		$(this).click(function() {
			$(this).parent().addClass('on').siblings('.on').removeClass();
			$($(this).attr('href')).addClass('on').siblings('.on').removeClass('on');
			// fake whash update
			window.location.hash = $(this).attr('title');
			// fake tab switch
			$('#pmedia').attr('class','t'+i);
			return false;
		});
		var hrf = '#' + $(this).attr('title');
		if((whash != '') && (hrf.indexOf(whash) > -1)) {
			$(this).click();
		}
	});
	
	$('#mphotos').append('<img id="mphoto" />');
	$('#mphotos a').click(function() {
		$('#mphoto').attr('src',$(this).attr('href'));
		return false;
	}).eq(0).click().siblings(':gt(5)').hide();
}

jQuery(function($) {
	if($('#main').hasClass('prop')) {
		propJS($);
	}
	
	$('#topnav ul li:first-child').addClass('f').parent().prev().addClass('f').bind('mouseover',function() {
		$(this).parent().addClass('over');
	}).parent().bind('mouseleave',function() {
		$(this).removeClass('over');
	});
	
	$('#ftabs a').click(function() {
		return false;
	});
	
	Cufon.replace('#topnav > li > a, #desc, #ftabs', { fontFamily: 'MrsEavesItalic' });
	Cufon.replace('#mtabs', { fontFamily: 'MrsEavesSmallCaps' });
	Cufon.now();
});