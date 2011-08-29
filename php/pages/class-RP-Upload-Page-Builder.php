<?php

class RP_Upload_Page_Builder {

    /**
     *
     * @param string $action
     * @param string $msg
     * @param array $options
     * @return string
     */
    function build($action, $msg = '', $options, $batchids) {

        $display = count( $batchids ) > 1? 'display:inline' : 'display:none';
        $default = '1';
        $block = '<div style="overflow:hidden;width:60%;margin:40px;"><div><i>'
                . __('Please note that GEDCOM processing is the most system taxing processing this plugin performs', 'rootspersona')
                . '. ' . __('The system will be uploading the file AND parsing each person into the database', 'rootspersona')
                . '. ' . __('Many users have reported hitting PHP time limit constraints when processing large files', 'rootspersona')
                . ', ' . __('resulting in a white screen with no status message', 'rootspersona')
                . '.</i></div>';

        $block .= "<form enctype='multipart/form-data' action='$action' method='POST'>"
                . "<div style='overflow:hidden;margin:10px;'>"
                . "<label class='label8' for='batchid'>Batch Id:</label>"
                . "<input type='text' name='batch_id' id='batch_id' size='6' value='$default'/>"
                
                . "<span style='overflow:hidden;margin:10px 10px 10px -10px;$display;'>"
                //. "<label class='label8' for='batchid'>&nbsp;</label>"
                . "<select id='batchids' name='batchids' style='zIndex=1;'"
                . " onchange='javascript:synchBatchText();'>";
        
       foreach ( $batchids as $id ) {
        //for ($id=1; $id< 10; $id++) {
            $selected = $id==$default?'selected':'';
           $block .= "<option value='$id' $selected>$id&nbsp;&nbsp;</option>";  
       }
                    
        $block .= "</select></span><span style='color:red;font-style:italic;margin-left:10px;font-size:smaller;'>"
                . sprintf( __('Please note that %s cannot link persons in different batches', 'rootspersona'), 'rootspersona')
                . "</span></div>"                
                
                . "<div style='overflow:hidden;margin:10px;'>"
                . "<label class='label8' for='gedcom'>GEDCOM File:</label><input type='file' name='gedcomFile' size='60'/></div>"

                . "<br/>&#160;&#160;<input type='submit' class='button' name='submitUploadGedcomForm' value='"
                . __('Upload', 'rootspersona') . "' onclick='document.body.style.cursor=\"wait\";'/>"
                . "&#160;&#160;<input type='reset' name='reset' value='"
                . __('Reset', 'rootspersona') . "'/>"
                . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>"
                . "</form><br/>";

        $block .= '<div>' . __('The following PHP settings have an impact on GEDCOM file processing', 'rootspersona') . ':<br/>'
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
