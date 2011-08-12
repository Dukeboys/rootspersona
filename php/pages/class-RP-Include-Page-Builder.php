<?php

class RP_Include_Page_Builder {

    /**
     *
     * @param array $persons
     * @param array $options
     * @param string $action
     * @param string $msg
     * @return string
     */
	function build( $persons, $options, $action, $msg = '' ) {
		$block = "<div style='overflow:hidden;width:60%;margin:40px;'><div class='personBanner'><br/></div>";
		if ( count( $persons ) == 0 ) {
			$block .=  "<br/><div style='text-align:center;color:red;font-weight:bold'>"
                    . sprintf( __( 'All %s have been included', 'rootspersona' ), "personas" )
                    . '. '
                    . sprintf( __('Previously excluded persons must be re-imported', 'rootspersona'))
                    . ".</div>";
		} else {
			$block .=  "<form  action='" . $action . "' method='POST'>"
                    .  "<br/><select multiple name='persons[]'  style='height:20em;'>";

            $cnt = count( $persons );
            if($cnt > 0) {
                for ( $i = 0; $i < $cnt; $i++ ) {
                    $block .=  "<option value='" . $persons[$i]->id . "'>"
                            . $persons[$i]->full_name . "</option>";
                }
            }

            $block .=  "</select><br/>"
                .  "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>"
                .  "<br/><input type='submit' name='submitIncludePageForm' value='"
                . __( 'Include', 'rootspersona' ) . "'/>"
                .  "&#160;&#160;<input type='reset' name='reset' value='"
                . __( 'Reset', 'rootspersona' ) . "'/>"
                .  "<br/><br/><div class='personBanner'><br/></div>"
                .  "</form></div>";
		}
		return $block;
	}
}
?>
