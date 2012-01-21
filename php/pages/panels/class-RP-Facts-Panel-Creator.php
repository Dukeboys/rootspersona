<?php

class RP_Facts_Panel_Creator {

    /**
     *
     * @param array $facts
     * @param array $options
     * @return string
     */
	public static function create( $facts, $options ) {
		$block = '<div class="rp_truncate">';
		$block .= '<div class="rp_facts">';
		$block .= '<ul>';
		$cnt = count( $facts );
		for ( $idx = 0; $idx < $cnt; $idx++ ) {
			$block .= '<li>';
			if ( ! $options['hide_dates']
			&& isset( $facts[$idx]['date'] )
			&& ! empty( $facts[$idx]['date'] ) ) {
				$block .= @preg_replace( '/@.*@(.*)/US', '$1', $facts[$idx]['date'] ) . ' - ';
			}
			$block .= $facts[$idx]['type'];
			if ( ! $options['hide_places']
			&& isset( $facts[$idx]['place'] )
			&& ! empty( $facts[$idx]['place'] ) ) {
				$block .= '; <span class="rp_place">' . $facts[$idx]['place'] . '</span>';
			}
			$p = $facts[$idx]['associated_person'];
			if ( isset( $p )
			&& ! empty( $p ) ) {
				$block .= ' ' . __( 'with', 'rootspersona' ) . ' ' . $p['name'];
			}
			$block .= '</li>';
		}
		$block .= '</ul></div></div>';
		return $block;
	}
}
?>
