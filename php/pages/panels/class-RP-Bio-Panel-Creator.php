<?php

class RP_Bio_Panel_Creator {

    /**
     *
     * @param array $facts
     * @param array $options
     * @return string
     */
	public static function create( $notes, $options ) {
		$block = '<div class="rp_truncate"><div class="rp_bio" style="margin:10px; 0px !important;">';
        $cnt = count( $notes);
        for ($idx = 0; $idx < $cnt; $idx++) {
            $block .= nl2br( $notes[$idx]->note ) ;
        }
        $block .= '</div></div>';
		return $block;
	}
}
?>
