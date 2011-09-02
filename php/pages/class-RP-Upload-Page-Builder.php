<?php

class RP_Upload_Page_Builder {

    /**
     *
     * @param string $action
     * @param string $msg
     * @param array $options
     * @return string
     */
    function build($action, $msg = '', $options, $batch_ids) {
        $default = count( $batch_ids ) > 0 ? $batch_ids[0] : '1';
        if(count($batch_ids) == 0) $batch_ids[0] = $default;
        $s = count( $batch_ids ) <= 1 ? '2' : '4';
        
        $block = '<div style="overflow:hidden;width:60%;margin:40px;"><div><i>'
                . __('Please note that GEDCOM processing is the most system taxing processing this plugin performs', 'rootspersona')
                . '. ' . __('The system will be uploading the file AND parsing each person into the database', 'rootspersona')
                . '. ' . __('Many users have reported hitting PHP time limit constraints when processing large files', 'rootspersona')
                . ', ' . __('resulting in a white screen with no status message', 'rootspersona')
                . '.</i></div>';
        
       if(function_exists( 'mysql_set_charset' ) ) {
           $block .= '<br/><div style="overflow:hidden;width:80%;color:red;border:#DDD solid 2px;background-color:#EEE;padding:10px;"><i>'
                . sprintf( __('%s is not able to find the %s function', 'rootspersona'), 'rootspersona', 'mysql_set_charset')
                . '. ' . __('This probably means your version of PHP is too old.  You must be running version 5.2.3 or higher', 'rootspersona')
                . '. ' . __('You will have problems uploading GEDCOM files until you upgrade', 'rootspersona')
                . ', ' . __('resulting in a white screen with no status message', 'rootspersona')
                . '.</i></div><br/>';
       }

        $block .= "<form enctype='multipart/form-data' action='$action' method='POST'>"
                . "<div style='overflow:hidden;margin:10px;'>"
                . "<label class='label8' for='batch_id'>Batch Id:</label>"
                . "<input type='text' name='batch_id' id='batch_id' size='6' value='$default'/>"
               . "<input type='button' hidefocus='1' value='&#9660;'"
                . "style='height:13;width:13;font-family:helvetica;padding:2px;' "
                . "onclick='javascript:if( jQuery(\"#batch_ids_span\").is(\":visible\") ) jQuery(\"#batch_ids_span\").css(\"display\",\"none\"); else jQuery(\"#batch_ids_span\").css(\"display\",\"inline-block\");'>"
                . "<span style='color:red;font-style:italic;margin-left:10px;font-size:smaller;'>"
                . sprintf( __('Please note that %s cannot link persons in different batches', 'rootspersona'), 'rootspersona')
                . "</span>"
                
                . "<br/><label class='label8' for='batch_id'>&nbsp;</label>"
                . "<span  id='batch_ids_span' name='batch_ids_span' style='display:none;overflow:hidden;margin:-3px 0px 0px 0px;'>"
                . "<select id='batch_ids' name='batch_ids' style='width:7.6em;zIndex=1;'"
                . " onchange='javascript:synchBatchText();' size='$s'>";
        
      foreach ( $batch_ids as $id ) {
           $selected = $id==$default?'selected':'';
           $block .= "<option value='$id' $selected>$id&nbsp;&nbsp;</option>";
      }
                    
        $block .= "</select></span></div>"                
                
                . "<div style='overflow:hidden;margin:10px;'>"
                . "<label class='label8' for='gedcom'>GEDCOM File:</label><input type='file' name='gedcomFile' size='60'/></div>"

                . "<br/>&#160;&#160;<input type='submit' class='button' name='submitUploadGedcomForm' value='"
                . __('Upload', 'rootspersona') . "' onclick='document.body.style.cursor=\"wait\";'/>"
                . "&#160;&#160;<input type='reset' name='reset' value='"
                . __('Reset', 'rootspersona') . "'/>"
                . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>"
                . "</form><br/>";

        $block .= '<div>' . __('The following PHP settings have an impact on GEDCOM file processing', 'rootspersona') . ':<br/>'
                . '<span style="color:blue;font-weight:bold">PHP ' . __('version','rootspersona') . '</span>'
                . ' <span style="color:green;font-weight:bold">' . phpversion() . '.</span></br/>'
                . '<span style="color:blue;font-weight:bold">upload_max_filesize</span>'
                . ' <span style="color:green;font-weight:bold">' . ini_get('upload_max_filesize') . '.</span></br/>'
                . '<span style="color:blue;font-weight:bold">post_max_size</span>'
                . ' <span style="color:green;font-weight:bold">' . ini_get('post_max_size') . '.</span></br/>'
                . '<span style="color:blue;font-weight:bold">max_input_time</span>'
                . ' <span style="color:green;font-weight:bold">' . ini_get('max_input_time') . '.</span></br/>'
                . '<span style="color:blue;font-weight:bold">max_execution_time</span>'
                . ' <span style="color:green;font-weight:bold">' . ini_get('max_execution_time') . '.</span></br/>'
                . '</div>';

        if(ini_get('safe_mode')) {
            $block .= '<div>' . __('Your system is configured in ', 'rootspersona') . ' '
                    . '<span style="color:red;font-weight:bold">safe_mode.</span> <i>rootspersona</i> '
                    . __('will NOT be able to override your system settings to allow processing of a large GEDCOM file.',
                            'rootspersna') . '</div>';
        } else {
            $block .= '<br/><div><i>rootspersona</i> '
                    . __('will try to override your system settings as needed to allow processing of a large GEDCOM file',
                            'rootspersona')
                    . '. '
                    . __('However you may still run into an issue with server time limit settings (usually 300 seconds)',
                            'rootspersona')
                    . ', ' .__('and may need to split your GEDCOM file accordingly',
                            'rootspersona')
                    . '.</div>';
        }
        $block .= '<br/><div>'
                    . __('You may change the above values in the php.ini file', 'rootspersona')
                    . ', '
                    . __('or by override the settings in your .htaccess file as defined below', 'rootspersona')
                    . ':<br/>php_value upload_max_filesize 10M<br/>'
                    . 'php_value post_max_size 10M<br/>'
                    . 'php_value max_input_time 300<br/>'
                    . 'php_value max_execution_time 300<br/>'
                    . '.</div></div>';
        return $block;
    }
}
?>
