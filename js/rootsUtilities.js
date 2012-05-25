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
    var msgs = jQuery('.persona_msg');
    jQuery.each(msgs, function(index, item){
        item.innerHTML = '';
    });
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(data) {
        document.body.style.cursor = "default";
        try {
            var obj = jQuery.parseJSON(data);
            var isErr = false;
            jQuery.each(obj, function(key, value){
                  if(key == 'error') {
                      var msgs = jQuery('.persona_msg');
                        jQuery.each(msgs, function(index, item){
                            item.innerHTML = value;
                            jQuery(item).css('color','red');
                        });
                        isErr = true;
                      return;
                  } else if (key == 'rp_id') {
                      jQuery("#rp_id").text(value);
                      jQuery('#personId').val(value);
                  } else if (key == 'rp_page') {
                      jQuery("#rp_page").text(value);
                      jQuery('#persona_page').val(value);
                  } else if (key == 'rp_famc') {
                      jQuery("#rp_famc").val(value);
                  } else if (key == 'rp_fams') {
                      var fams = jQuery('.rp_fams');
                      jQuery.each(fams, function(index, item){
                        if(jQuery(item).val() == '-1') {
                            jQuery(item).val(value);
                            return;
                        }
                      });
                  }
            });

            jQuery("#rp_father_id").val('');
            jQuery("#rp_mother_id").val('');
            jQuery('#spousal_link').css('display','inline');

            if(!isErr) {
                var msgs = jQuery('.persona_msg');
                jQuery.each(msgs, function(index, item){
                    item.innerHTML = 'Saved.';
                    jQuery(item).css('color','green');
                });
                var cancels = jQuery('[name="cancel"]');
                jQuery.each(cancels, function(index, item){
                    jQuery(item).val('View');
                });
            }
        } catch (err) {
            if (data == null) data = err;
            alert(data);
        }

    });
}

function unlinkparents(fid) {
    jQuery('#' + fid).val('');
    jQuery('#rp_father').html('');
    jQuery('#rp_mother').html('');
    jQuery('#rp_unlink_parents').css('display','none');
    jQuery('#rp_link_parents1').css('display','inline');
    jQuery('#rp_link_parents2').css('display','inline');
    jQuery('#parental_text').val('');
    jQuery('#parental_text').css('display','none');
    jQuery('#rp_father_id').val('');
    jQuery('#rp_mother_id').val('');
    jQuery('#parental_gender').val('');
}

function linkparents(seq) {
    if(jQuery('#parental_text').is(':visible')) {
        jQuery('#parental_text').css('display','none');
        jQuery('#parental_text').val('');
        jQuery('#parental_gender').val('');
    } else {
        jQuery('#parental_text').css('display','inline');
        jQuery('#parental_gender').css('display','inline');
        jQuery('#parental_gender').val(seq);
    }
}

function unlinkspouse(fid) {
    jQuery('#' + fid).val('');
    famid = fid.replace('rp_fams_','');
    jQuery('#rp_group_' + famid).remove();
    jQuery('#spousal_text').css('display','none');
    jQuery('#spousal_link').css('display','inline');
}

function linkspouse() {
    if(jQuery('#spousal_text').is(':visible')) {
        jQuery('#spousal_text').css('display','none');
        jQuery('#spousal_text').val('');
    } else {
        jQuery('#spousal_text').css('display','inline');
    }
}


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
                  if(index == 'error') {
                      alert(p);
                      return;
                  } else {
                    // add items to List box
                    jQuery("#persons").append("<option id='" + p.id + "'>"
                        + p.surname + ", " + p.given + "</option");
                }
            } // end of function
        );  // each
	});

    return false;
}

jQuery(document).ready(function() {

    var rpformfield;

    rp_send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        jQuery('#' + rpformfield).val(imgurl);
        imgid = rpformfield.replace('_path','');
        jQuery('#' + imgid).attr('src',imgurl);
        jQuery('#' + imgid).parent().attr('href',imgurl);
        tb_remove();
    }

    jQuery('#img_1_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_1').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_2_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_2').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_3_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_3').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_4_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_4').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_5_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_5').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_6_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_6').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('#img_7_upload_button').click(function() {
        window.send_to_editor = rp_send_to_editor;
        rpformfield = jQuery('#img_path_7').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
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

    jQuery('.submitPersonForm').each(function(index) {
        jQuery(this).mouseover(function() {
           jQuery(this).removeClass('submitPersonForm').addClass('submitPersonFormHover');
        });
        jQuery(this).mouseout(function() {

           jQuery(this).removeClass('submitPersonFormHover').addClass('submitPersonForm');
        });
        jQuery(this).mousedown(function() {
           jQuery(this).removeClass('submitPersonFormHover').addClass('submitPersonFormClick');
        });
       jQuery(this).mouseup(function() {
           jQuery(this).removeClass('submitPersonFormClick').addClass('submitPersonFormHover');
        });
    });

    function rp_autoSelectPerson (event, ui) {
            var seq;
            var el;
            var nameParm;
            var ssid;
            var block;
            var name;
            var type = this.id.replace('_text','');
            var end = ui.item.value.indexOf(')');
            var idParm = ui.item.value.substr(1, (end-1)).split('-');
            if(type == 'parental' && idParm[0] == 'F') {
                jQuery('#rp_famc').val(idParm[1]);
                nameParm = ui.item.value.substr(end+2).split('&')
                jQuery('#rp_father').html(nameParm[0]);
                jQuery('#rp_mother').html(nameParm[1]);
                jQuery('#rp_unlink_parents').css('display','inline');
                jQuery('#rp_link_parents1').css('display','none');
                jQuery('#rp_link_parents2').css('display','none');
                jQuery('#rp_father_id').val('');
                jQuery('#rp_mother_id').val('');
                jQuery('#parental_text').val('');
                jQuery('#parental_gender').val('');
                jQuery('#parental_text').css('display','none');
            } else if(type == 'parental' && idParm[0] == 'I') {
                el = jQuery('#rp_famc')
                var id = el.val();
                if ( id == '') id = '-1';
                el.val(id);
                nameParm = ui.item.value.split(')');
                name = nameParm[1].substr(1);
                if(jQuery('#parental_gender').val() == '1') {
                    jQuery('#rp_father').html(name);
                    jQuery('#rp_father_id').val(idParm[1]);
                    jQuery('#rp_link_parents1').css('display','none');
                } else {
                    jQuery('#rp_mother').html(name);
                    jQuery('#rp_mother_id').val(idParm[1]);
                    jQuery('#rp_link_parents2').css('display','none');
                }
                jQuery('#rp_unlink_parents').css('display','inline');
                jQuery('#parental_text').css('display','none');
                jQuery('#parental_text').val('');
            } else if (idParm[0] == 'F1' || idParm[0] == 'F2') {
                seq = 0;
                el = jQuery('#rp_sseq_' + seq);
                while (el.length > 0) {
                    seq++;
                    el = jQuery('#rp_sseq_' + seq);
                }
                nameParm = ui.item.value.substr(end+2).split('&');
                ssid = nameParm[1].substr(9, nameParm[1].indexOf(']')-9);
                block = '<div id="rp_group_' + seq + '" name="rp_group_' + seq + '">'
                    + '<div style="margin-left:10px;"><span style="display:inline-block;width:23.5em;">Family ' + idParm[1] + '</span>'
                    + '<input type="hidden" id="rp_sseq_'
                    + seq + '" name="rp_sseq_'+ seq + '" value="' + (idParm[0]=='F2'?'2':'1') + '">'
                    + '<input type="hidden" id="rp_sid_'+ seq + '" name="rp_sid_'+ seq + '" value="' + ssid + '">'
                    + '<input type="hidden" class="rp_fams" id="rp_fams_'+ seq + '" name="rp_fams_'+ seq + '" value="' + idParm[1] + '">'
                    + '<input id="rp_fams_unlink_'+ seq + '" name="rp_fams_unlink_'+ seq + '" class="submitPersonForm" type="button" onclick="unlinkspouse(\'rp_fams_'+ seq + '\');"  value="'
                    + 'Unlink from this Family Group"></div>'
                    + '<div style="margin-left:20px;"><span style="font-weight:bold;font-style:italic;display:inline-block;width:5em;">Spouse: </span>'
                    + nameParm[0] + '</div></div>';
                jQuery('#spousal_text').parent().prepend(block);
                jQuery('#spousal_text').val('');
                jQuery('#spousal_text').css('display','none');
            } else if (idParm[0] == 'I') {
                seq = 0;
                el = jQuery('#rp_sseq_' + seq);
                while (el.length > 0) {
                    seq++;
                    el = jQuery('#rp_sseq_' + seq);
                }
                nameParm = ui.item.value.split(')');
                name = nameParm[1].substr(1);
                block = '<div id="rp_group_' + seq + '" name="rp_group_' + seq + '">'
                    + '<div style="margin-left:10px;"><span style="display:inline-block;width:23.5em;">Family</span>'
                    + '<input type="hidden" id="rp_sseq_' + seq + '" name="rp_sseq_'+ seq + '">'
                    + '<input type="hidden" id="rp_sid_'+ seq + '" name="rp_sid_'+ seq + '" value="' + idParm[1] + '">'
                    + '<input type="hidden" class="rp_fams" id="rp_fams_'+ seq + '" name="rp_fams_'+ seq + '" value="-1">'
                    + '<input id="rp_fams_unlink_'+ seq + '" name="rp_fams_unlink_'+ seq + '" class="submitPersonForm" type="button" onclick="unlinkspouse(\'rp_fams_'+ seq + '\');"  value="'
                    + 'Unlink from this Family Group"></div>'
                    + '<div style="margin-left:20px;"><span style="font-weight:bold;font-style:italic;display:inline-block;width:5em;">Spouse: </span>'
                    + name + '</div></div>';
                jQuery('#spousal_text').parent().prepend(block);
                jQuery('#spousal_text').val('');
                jQuery('#spousal_text').css('display','none');
                jQuery('#spousal_link').css('display','none');
            }
    }

    function rp_autoCompleteParental(req, add){
        document.body.style.cursor = "wait";
        jQuery('#parental_text').css('cursor','wait');
        var data = {
            action: 'rp_action',
            datastr: req,
            form_action: 'getFamilies'
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(data) {
            //create array for response objects
            var suggestions = [];

            //process response
            rows = jQuery.parseJSON(data);
            if( rows !== undefined && rows != null ) {
                jQuery.each(rows, function(idx, row){
                  if(idx == 'error') {
                      alert(row);
                      return;
                  } else {
                    suggestions.push(row.name);
                  }
                });
            }

            //pass array to callback
            add(suggestions);

            document.body.style.cursor = "default";
            jQuery('#parental_text').css('cursor','default');
        });
    }

    function rp_autoCompleteSpousal(req, add){
        document.body.style.cursor = "wait";
        jQuery('#spousal_text').css('cursor','wait');
        var data = {
            action: 'rp_action',
            datastr: req,
            form_action: 'getSpouses'
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(data) {
            //create array for response objects
            var suggestions = [];

            //process response
            rows = jQuery.parseJSON(data);
            if( rows !== undefined && rows != null ) {
                jQuery.each(rows, function(idx, row){
                  if(idx == 'error') {
                      alert(row);
                      return;
                  } else {
                    suggestions.push(row.name);
                  }
                });
            }

            //pass array to callback
            add(suggestions);

            document.body.style.cursor = "default";
            jQuery('#spousal_text').css('cursor','default');
        });
    }

    jQuery('#parental_text').autocomplete({
        source: rp_autoCompleteParental,
        select: rp_autoSelectPerson,
        minLength: 1
    });

    jQuery('#spousal_text').autocomplete({
        source: rp_autoCompleteSpousal,
        select: rp_autoSelectPerson,
        minLength: 1
    });

    document.body.style.cursor = "default";

});