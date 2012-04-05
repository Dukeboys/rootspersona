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
            jQuery('#batchspan').offset({ top: (caller.top - 25), left: (caller.left - 10) });
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
        action: 'my_action',
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

var tooltip=function(){
    var id = 'tt';
    var top = 3;
    var left = 3;
    var maxw = 300;
    var speed = 10;
    var timer = 20;
    var endalpha = 95;
    var alpha = 0;
    var tt,t,c,b,h;
    var ie = document.all ? true : false;
    return{
        show:function(v,w){
            if(tt == null){
                tt = document.createElement('div');
                tt.setAttribute('id',id);
                t = document.createElement('div');
                t.setAttribute('id',id + 'top');
                c = document.createElement('div');
                c.setAttribute('id',id + 'cont');
                b = document.createElement('div');
                b.setAttribute('id',id + 'bot');
                tt.appendChild(t);
                tt.appendChild(c);
                tt.appendChild(b);
                document.body.appendChild(tt);
                tt.style.opacity = 0;
                tt.style.filter = 'alpha(opacity=0)';
                document.onmousemove = this.pos;
            }
            tt.style.display = 'block';
            c.innerHTML = v;
            tt.style.width = w ? w + 'px' : 'auto';
            if(!w && ie){
                t.style.display = 'none';
                b.style.display = 'none';
                tt.style.width = tt.offsetWidth;
                t.style.display = 'block';
                b.style.display = 'block';
            }
            if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
            h = parseInt(tt.offsetHeight) + top;
            clearInterval(tt.timer);
            tt.timer = setInterval(function(){tooltip.fade(1)},timer);
        },
        pos:function(e){
            var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
            var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
            tt.style.top = (u - h) + 'px';
            tt.style.left = (l + left) + 'px';
        },
        fade:function(d){
            var a = alpha;
            if((a != endalpha && d == 1) || (a != 0 && d == -1)){
                var i = speed;
                if(endalpha - a < speed && d == 1){
                    i = endalpha - a;
                }else if(alpha < speed && d == -1){
                    i = a;
                }
                alpha = a + (i * d);
                tt.style.opacity = alpha * .01;
                tt.style.filter = 'alpha(opacity=' + alpha + ')';
            }else{
                clearInterval(tt.timer);
                if(d == -1){tt.style.display = 'none'}
            }
        },
        hide:function(){
            clearInterval(tt.timer);
            tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
        }
    };
}();
