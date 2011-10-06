<?php

class RP_Descendancy_Panel_Creator {

    /**
     *
     * @param array $descendants
     * @param array $options
     * @return string
     */
	public static function create( $descendants, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

		$block = '<div class="rp_truncate">'
                . '<div class="rp_descendants">';

		$block .= '</div></div>';
		return $block;
	}
}
?>
