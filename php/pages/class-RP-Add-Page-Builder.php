<?php

class RP_Add_Page_Builder {

    /**
     *
     * @param string $action
     * @param array $persons
     * @param string $msg
     * @param array $options
     * @return string
     */
	function build( $action, $persons, $msg = '', $options, $batch_ids=array(1) ) {
        $default = count( $batch_ids ) > 0 ? $batch_ids[0] : '1';
        if(count($batch_ids) == 0) $batch_ids[0] = $default;
        $s = count( $batch_ids ) <= 1 ? '2' : '4';
        
		$block = "<div style='overflow:hidden;width:60%;margin:40px;'><div class='personBanner'>&#160;</div>";
		if ( count( $persons ) == 0 ) {
			$block .= "<br/><div style='text-align:center;color:red;font-weight:bold'>"
                    . __( 'All available persons have been added.', 'rootspersona' ) . "</div>";
		} //else {
			$block .= "<form  action='" . $action . "' method='POST'>"
                . "<div style='overflow:hidden;margin:10px;'>"
                . "<label class='label4' for='batch_id'>Batch Id:</label>"
                . "<input type='text' name='batch_id' id='batch_id' size='6' value='$default'/>"
               . "<input type='button' hidefocus='1' value='&#9660;'"
                . "style='height:13;width:13;font-family:helvetica;padding:2px;' "
                . "onclick='javascript:if( jQuery(\"#batch_ids_span\").is(\":visible\") ) jQuery(\"#batch_ids_span\").css(\"display\",\"none\"); else jQuery(\"#batch_ids_span\").css(\"display\",\"inline-block\");'>"
                
                . "<br/><label class='label4' for='batch_id'>&nbsp;</label>"
                . "<span  id='batch_ids_span' name='batch_ids_span' style='display:none;overflow:hidden;margin:-3px 0px 0px 0px;'>"
                . "<select id='batch_ids' name='batch_ids' style='width:7.6em;zIndex=1;'"
                . " onchange='javascript:synchBatchText();' size='$s'>";
        
          foreach ( $batch_ids as $id ) {
               $selected = $id==$default?'selected':'';
               $block .= "<option value='$id' $selected>$id&nbsp;&nbsp;</option>";
          }
                    
        $block .= "</select></span></div>";
        
        $cnt = count( $persons );
        $height = ( $cnt > 10 ? '40em' : ( $cnt > 0 ? '15em' : '5em;') );
        $w = count( $persons ) == 0 ? 'width:13em;' : '';
        $block .= "<table style='border-style:none;'><tbody><tr><td width='49%'>"
                    . "<select multiple name='persons[]' id='persons' style='height:$height;$w'>";
                    $cnt = count( $persons );
                    for ( $i = 0; $i < $cnt; $i++ ) {
                        $block .= "<option value='" . $persons[$i]['id'] . "'>"
                                . $persons[$i]['surname'] . ', '
                                . $persons[$i]['given'] . "</option>";
                    }
			$block .= "</select></td>" . "<td></td>"
                    . "</tr><tr><td>"
                    . "<div style='text-align:center;color:red;font-weight:bold'>"
                    . $msg . "</div>"
                    . "<br/><input type='submit' name='submitAddPageForm' value='"
                    . __( 'Add', 'rootspersona' ) . "' onclick='document.body.style.cursor=\"wait\";'/>" 
                    . "&#160;&#160;<input type='reset' name='reset' value='"
                    . __( 'Reset', 'rootspersona' ) . "'/>"
                    . "</td><td/></tr></tbody></table><br/><br/><div class='personBanner'><br/></div>"
                    . "</form></div>";
		//}
		return $block;
	}
}
?>
