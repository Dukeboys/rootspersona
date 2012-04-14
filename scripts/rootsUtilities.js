function rootsConfirm(winText, trueAction) {
    var r = confirm(winText);
    if (r == true) {
        window.location = trueAction;
    } else {
        window.location = "#";
    }
}

function findPersonaPage(site_url) {
    var e = document.getElementById("persona_page");
    var page = e.options[e.selectedIndex].value;
    window.location=site_url + '?page_id=' + page;
}

function gotoPersonaPage(site_url) {
    var e = document.getElementById("persona_page");
    var page = e.value;
    window.location=site_url + '?page_id=' + page;
}

function updatePersona() {
        var postData = jQuery('#editPersonaForm').serialize();
        var data = {
            action: 'rp_action',
            datastr: postData,
            form_action: 'updatePersona'
        };
        document.body.style.cursor = "wait";
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(data) {
            //alert(data);
            var obj = jQuery.parseJSON(data);
            isErr = false;
            document.body.style.cursor = "default";
        });
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

    var claimTypes = [
        "Adoption",
        "Adult Christening",
        "Annulment",
        "Baptism",
        "Bar Mitzvah",
        "Bas Mitzvah",
        "Birth",
        "Blessing",
        "Burial",
        "Caste",
        "Census",
        "Christening",
        "Confirmation",
        "Cremation",
        "Death",
        "Divorce",
        "Divorce Filed",
        "Education",
        "Emmigration",
        "Engagement",
        "Event",
        "Fact",
        "First Communion",
        "Graduation",
        "Head of House",
        "Immigration",
        "Marriage",
        "Marriage Bann",
        "Marriage Contract",
        "Marriage License",
        "Marriage Settlement",
        "Nationality",
        "Naturalization",
        "Nobility Title",
        "Occupation",
        "Ordnance",
        "Possessions",
        "Probate",
        "Religion",
        "Residence",
        "Retirement",
        "Social Security Nbr",
        "Will"
	];

    jQuery('.claimType').each(function(index) {
            jQuery(this).autocomplete({
                source: claimTypes
            });
    });

    function addFactsRow() {
        var claim = jQuery('#newclaim').val();
        if(claim != null && claim != "") {
            var newRow = '<tr><td><input id="newclaim" type="text" class="claimType" value=""></td>'
                        + '<td><input id="newdate" type="text" value=""></td>'
                        + '<td><input id="newplace" type="text" value=""></td>'
                        + '<td><textarea id="newclassification" type="text" cols="30" rows="1"></textarea>'
                        + '<td id="newbutton"></td></tr>';
            var delPath = jQuery('#imgPath').val() + 'delete-icon.png';

            var claimfield = jQuery('#newclaim');
            var datefield  = jQuery('#newdate');
            var placefield  = jQuery('#newplace');
            var classfield  = jQuery('#newclassification');
            var buttoncell = jQuery('#newbutton');

            buttoncell.append('<img alt="Delete" src="' + delPath + '" class="delFacts"/>');
            buttoncell.children().each(function(index) {
                jQuery(this).click(deleteFactsRow);
                jQuery(this).mouseover(function() {
                    var imgPath = jQuery('#imgPath').val() + 'delete-icon-hover.png';
                    jQuery(this).attr('src',imgPath);
                });
                jQuery(this).mouseout(function() {
                    var imgPath = jQuery('#imgPath').val() + 'delete-icon.png';
                    jQuery(this).attr('src',imgPath);
                });
                jQuery(this).mousedown(function() {
                    var imgPath = jQuery('#imgPath').val() + 'delete-icon-click.png';
                    jQuery(this).attr('src',imgPath);
                });
            });
            var tbody = jQuery('#facts');
            tbody.append(newRow);

            var rowCnt = tbody.children().length - 2;
            claimfield.removeAttr('id');
            datefield.removeAttr('id');
            placefield.removeAttr('id');
            classfield.removeAttr('id');
            buttoncell.removeAttr('id');
            claimfield.attr('name', 'rp_claimtype_' + rowCnt);
            datefield.attr('name', 'rp_claimdate_' + rowCnt);
            placefield.attr('name', 'rp_claimplace_' + rowCnt);
            classfield.attr('name', 'rp_classification_' + rowCnt);
            classfield.unbind('blur');

            jQuery('#facts > tbody').each(function(index) {
                jQuery(this).append(newRow);
            });
            jQuery('#newclaim').autocomplete({
                source: claimTypes
            });
            jQuery('#newclaim').focus();
            jQuery('#newclassification').blur(addFactsRow);
        }
    }

    jQuery('#newclassification').blur(addFactsRow);

    function deleteFactsRow() {
        // img > cell > row
        jQuery(this).parent().parent().remove();
        var tbody = jQuery('#facts');
        var cnt = tbody.children().length - 1;
        tbody.children().each(function(index) {
            var trow = jQuery(this);
            var cells = trow.children("td");
            var inp = cells.eq(0).children().eq(0);
            if(inp.attr("id") == "newclaim" ) return;

            inp.attr("name","rp_claimtype_" + index);
            inp = cells.eq(1).children().eq(0);
            inp.attr("name","rp_claimdate_" + index);
            inp = cells.eq(2).children().eq(0);
            inp.attr("name","rp_claimplace_" + index);
            inp = cells.eq(3).children().eq(0);
            inp.attr("name","rp_classification_" + index);
        });
    }

    jQuery('.delFacts').each(function(index) {
        jQuery(this).click(deleteFactsRow);
        jQuery(this).mouseover(function() {
           var imgPath = jQuery('#imgPath').val() + 'delete-icon-hover.png';
           jQuery(this).attr('src',imgPath);
        });
        jQuery(this).mouseout(function() {
           var imgPath = jQuery('#imgPath').val() + 'delete-icon.png';
           jQuery(this).attr('src',imgPath);
        });
        jQuery(this).mousedown(function() {
           var imgPath = jQuery('#imgPath').val() + 'delete-icon-click.png';
           jQuery(this).attr('src',imgPath);
        });
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
    var action = '';
    if (obj.id == 'evidence') {
        action = 'evidence';
    } else if(obj.id == 'delete') {
        action = 'delete'
    } else if(obj.id == 'validate') {
        action = 'validatePages';
    } else if(obj.id == 'review') {
        action='include';
    }
    var b = jQuery('#batch_ids option')
    if ( b.size() < 2 ) {
        batch_id = b.val();
        var event = new Object();
        event.data = new Object();
        event.data.url = url + '/tools.php?page=rootsPersona&rootspage=';
        event.data.action = action;
        processBatchSpan(event);
    } else {
        var spanpos = jQuery('#' + obj.id).parent().next().children('span:first');

        if( jQuery('#batchspan').is(":visible") ) {
            jQuery('#batchspan').hide();
        } else {
            jQuery('#process_button').bind('click',{url: url, action: action}, processBatchSpan);

            var caller = spanpos.offset();
            jQuery('#batchspan').show();
            jQuery('#batchspan').offset({top: (caller.top - 25), left: (caller.left - 10)});
        }
    }
}

function synchBatchText() {
    var value = jQuery('#batch_ids option:selected').val();
    jQuery('#batch_id').val(value);
    return false;
}

function refreshAddPerson() {
    var b = jQuery('#batch_ids option:selected').val();
    jQuery("#persons").contents().remove();
	var data = {
        action: 'rp_action',
        refresh: 1,
        batch_id: b
	};
    document.body.style.cursor = "wait";
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
        document.body.style.cursor = "default";
        var res = jQuery.parseJSON(response);
        jQuery.each(res, function(index, p) {
            // add items to List box
            jQuery("#persons").append("<option id='" + p.id + "'>"
                        + p.surname + ", " + p.given + "</option");
            } // end of function
        );  // each
	});

    return false;
}