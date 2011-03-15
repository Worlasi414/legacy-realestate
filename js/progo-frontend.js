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
			var tar = $(this).attr('href');
			$(tar).addClass('on').siblings('.on').removeClass('on');
			// error correct location GMAP
			if(tar == '#map') {
				tar = $(tar).children('iframe');
				var osrc = tar.attr('src');
				tar.attr('src',osrc);
			}
			
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
	var photos = $('#mphotos a');
	
	photos.click(function() {
		$('#mphoto').attr('src',$(this).attr('href'));
		return false;
	}).eq(0).click();
	
	if( photos.size() < 2 ) {
		$('#mphotos .ngg-galleryoverview').hide();
	} else if( photos.size() > 7 ) {
		photos.slice(7).hide();
		$('#mphotos').append('<a href="#p" class="arr p off" /><a href="#n" class="arr n" />')
			.children('a.arr').click(function() {
				if( $(this).hasClass('off') == false ) {
					var vis = $('#mphotos a.thm:visible');
					if( $(this).hasClass('n') ) {
						$(this).prev().removeClass('off');
						vis.filter(':first').hide();
						var nx = vis.filter(':last').next().show();
						if(nx.next().size()==0) {
							$(this).addClass('off');
						}
					} else {
						$(this).next().removeClass('off');
						vis.filter(':last').hide();
						var nx = vis.filter(':first').prev().show();
						if(nx.prev().size()==0) {
							$(this).addClass('off');
						}
					}
				}
				return false;
			});
	}
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
		var par = $(this).parent();
		if(par.hasClass('on') == false) {
			par.addClass('on').siblings().removeClass('on');
			if(par.hasClass('first') ) {
				par.parent().parent().removeClass('t2');
			} else {
				par.parent().parent().addClass('t2');
			}
			$($(this).attr('href')).addClass('on').siblings('.ftab').removeClass('on');
		}
		return false;
	});
	
	Cufon.replace('#topnav > li > a, #desc, #ftabs', { fontFamily: 'MrsEavesItalic' });
	Cufon.replace('#mtabs', { fontFamily: 'MrsEavesSmallCaps' });
	Cufon.now();
});