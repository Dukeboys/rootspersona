<?php

class RP_Bio_Panel_Creator {

    /**
     *
     * @param array $facts
     * @param array $options
     * @return string
     */
	public static function create( $notes, $options ) {
		$block = '<section class="rp_truncate">'
               . RP_Persona_Helper::get_banner($options, __( 'Biography', 'rootspersona' ) )
               . '<div class="rp_bio" style="margin:10px; 0px !important;">';
        $cnt = count( $notes);
        for ($idx = 0; $idx < $cnt; $idx++) {
            $block .= nl2br( $notes[$idx]->note ) ;
        }
        $block .= '</div></section>';
		return $block;
	}

    public static function create_for_edit( $notes, $options ) {
		$block = '<section class="rp_truncate">'
               . RP_Persona_Helper::get_banner($options, __( 'Biography', 'rootspersona' ) )
               . '<div class="rp_bio" style="margin:10px; 0px !important;">';
        $cnt = count( $notes);
        for ($idx = 0; $idx < $cnt; $idx++) {
            $block .= '<textarea id="rp_bio" name="rp_bio" cols="120" rows="5">' . $notes[$idx]->note . '</textarea><br/>' ;
        }
        $block .= '</div></section>';
		return $block;
    }
}
?>
