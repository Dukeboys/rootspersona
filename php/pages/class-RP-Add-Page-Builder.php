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
	function build( $action, $persons, $msg = '', $options ) {
		$block = "<div class='personBanner'>&#160;</div>";
		if ( count( $persons ) == 0 ) {
			$block .= "<br/><div style='text-align:center;color:red;font-weight:bold'>" . __( 'All available persons have been added.', 'rootspersona' ) . "</div>";
		} else {
			$block .= "<form  action='" . $action . "' method='POST'>" . "<table style='border-style:none;'><tbody><tr><td width='49%'>" . "<select multiple name='persons[]' size='20'>";
			$cnt = count( $persons );
			for ( $i = 0;
	$i < $cnt;
	$i++ ) {
				$block .= "<option value='" . $persons[$i]['id'] . "'>" . $persons[$i]['surname'] . ', ' . $persons[$i]['given'] . "</option>";
			}
			$block .= "</select></td>" . "<td></td>" . "</tr><tr><td>" . "<div style='text-align:center;color:red;font-weight:bold'>" . $msg . "</div>" . "<br/><input type='submit' name='submitAddPageForm' value='" . __( 'Add', 'rootspersona' ) . "'/>" . "&#160;&#160;<input type='reset' name='reset' value='" . __( 'Reset', 'rootspersona' ) . "'/>" . "</td><td/></tr></tbody></table><br/><br/><div class='personBanner'><br/></div>" . "</form>";
		}
		return $block;
	}
}
?>
