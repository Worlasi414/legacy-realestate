// js for front end of ProGo Themes RealEstate sites

function checkReq() {
	var aok = true;
	jQuery('form.pform .need').removeClass('.need');
	jQuery('form.pform .req').each(function() {
		if(jQuery(this).val()=='') {
			aok = false;
			jQuery(this).addClass('need');
		}
	});
	if(!aok) alert('Please check all *Required fields.');
	return aok; 
}

function checkThisField() {
	if(jQuery(this).val()=='') jQuery(this).addClass('need');
	else jQuery(this).removeClass('need');
}

function proGoTwitterCallback(twitters) {
  for (var i=0; i<twitters.length; i++){
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });
    jQuery('.tweets p.last').before('<p>'+status+'<br /><a href="http://twitter.com/'+twitters[i].user.screen_name+'/status/'+twitters[i].id_str+'" target="_blank">'+relative_time(twitters[i].created_at)+'</a> via '+ twitters[i].source +'</p>');
  }
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}

jQuery(function($) {
	$('#edit').change(function() {
		$('#billing,#shipping').toggle();
	});
	$('form.pform input.req').bind('blur',checkThisField);
	$('form.pform select.req').bind('change',checkThisField);
	$('form.pform').bind('submit',checkReq);
	
	$('#side .editchecks input:checkbox').click(function() {
		var check = $(this).attr('checked');
		var show = 'payment';
		if(check) {
			show = $(this).attr('name') == 'editbilling' ? 'billing' : 'shipping';
		}
		$('#'+show).show().siblings('fieldset').hide();
		$(this).parent().siblings('label').children('input:checkbox').attr('checked',false);
	});
});