function rootsConfirm(winText, trueAction) {
    var r = confirm(winText);
    if (r == true) {
        window.location = trueAction;
    } else {
        window.location = "#";
    }
}

function findPage(site_url) {
    var e = document.getElementById("persona_page");
    var page = e.options[e.selectedIndex].value;
    window.location=site_url + '?page_id=' + page;
}

jQuery(document).ready(function() {
    var formfield;
    jQuery('#img1_upload_button').click(function() {
        formfield = jQuery('#img1_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img2_upload_button').click(function() {
        formfield = jQuery('#img2_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img3_upload_button').click(function() {
        formfield = jQuery('#img3_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img4_upload_button').click(function() {
        formfield = jQuery('#img4_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img5_upload_button').click(function() {
        formfield = jQuery('#img5_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img6_upload_button').click(function() {
        formfield = jQuery('#img6_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img7_upload_button').click(function() {
        formfield = jQuery('#img7_upload').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        jQuery('#' + formfield).val(imgurl);
        imgid = formfield.substr(0,4);
        jQuery('#' + imgid).attr('src',imgurl);
        jQuery('#' + imgid).parent().attr('href',imgurl);
        tb_remove();
    }

    document.body.style.cursor = "default";

});

function processBatchSpan( event ) {
    var myUrl = event.data.url
                + '/tools.php?page=rootsPersona&rootspage=';
    var action = event.data.action;
    if (action == 'include') {
        myUrl += action + '&batch_id=' + jQuery('#batch_id').val();
    } else {
        myUrl += 'util&utilityAction='
            + action + '&batch_id=' + jQuery('#batch_id').val();
    }

    jQuery('#process_button').unbind('click', processBatchSpan);
    window.location = myUrl;
    return false;
}

function revealBatchSpan(obj, url) {
    var spanpos = jQuery('#' + obj.id).parent().next().children('span:first');

    if( jQuery('#batchspan').is(":visible") ) {
        jQuery('#batchspan').hide();
        spanpos.show();
    } else {
        var action = '';
        if (obj.id == 'evidence') {
            action = 'addEvidencePages';
        } else if(obj.id == 'delete') {
            action = 'delete'
        } else if(obj.id == 'validate') {
            action = 'validatePages';
        } else if(obj.id == 'review') {
            action='include';
        }

        jQuery('#process_button').bind('click',{url: url, action: action}, processBatchSpan);

        var caller = spanpos.offset();
        spanpos.hide();
        jQuery('#batchspan').show();
        jQuery('#batchspan').offset({ top: caller.top, left: caller.left });
    }
}

function synchBatchText(name) {
    var value = jQuery('#batch_ids option:selected').val();
    jQuery('#batch_id').val(value);
    return false;
}

function refreshAddPerson() {
    var batchid = jQuery('#batchids option:selected').val();

	var data = {
        action: 'my_action',
        refresh: 1,
        batch_id: batchid
	};
    document.body.style.cursor = "wait";
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
        document.body.style.cursor = "default";
        var res = jQuery.parseJSON(response);
    jQuery("#persons").contents().remove();
        jQuery.each(res, function(index,p) {
            // add items to List box
            jQuery("#persons").append("<option id='" + p.id + "'>"
                        + p.surname + ", " + p.given + "</option");
            } // end of function
        );  // each
	});

    return false;
}


