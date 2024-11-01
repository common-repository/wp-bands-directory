jQuery(document).ready(function(){
 
    jQuery(".addButton").click(function () {
		var basefieldname = jQuery(this).attr("rel");
		var counter = jQuery("#"+basefieldname+"itemcount").attr("rel");
		var prev_count = counter-1;
		var fieldtype = jQuery("#"+basefieldname+"fieldtype").attr("rel");
		
		if(fieldtype=="select"){
			jQuery(document.createElement('div'))
				.attr('id',basefieldname+'ItemDiv'+counter)
				.html('<select id="'+basefieldname+'_'+counter+'" name="'+basefieldname+'_'+counter+'"></select>')
				.appendTo('#'+basefieldname+'Group');
			jQuery('select#'+basefieldname+'_'+prev_count+' option').clone().appendTo('select#'+basefieldname+'_'+counter);

		}else{
			jQuery(document.createElement('div'))
				.attr('id',basefieldname+'ItemDiv'+counter)
				.html('<input type="text" name="'+basefieldname+'_'+counter+'" id="'+basefieldname+'_'+counter+'" value="" >')
				.appendTo('#'+basefieldname+'Group');
		}
		
		counter++;
		jQuery("#"+basefieldname+"itemcount").attr('rel',counter);
     });
 
    jQuery(".removeButton").click(function () {
	var basefieldname = jQuery(this).attr("rel");
	var counter = jQuery("#"+basefieldname+"itemcount").attr("rel");
	    counter--;
        jQuery("#"+basefieldname+"ItemDiv"+counter).remove();
        jQuery("#"+basefieldname+"itemcount").attr('rel',counter);
    });
    
    jQuery('#edit-slug-box').hide();
  });

