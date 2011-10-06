<?php

class RP_Descendancy_Panel_Creator {

    /**
     *
     * @param array $descendants
     * @param array $options
     * @return string
     */
	public static function create( $persona, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

		$block = '<div class="rp_truncate">'
                . '<div class="rp_descendants" style="padding:10px 4px;">';
        $block .= RP_Descendancy_Panel_Creator::build_level( $persona, $options, 1 );
		$block .= '</div></div>';
		return $block;
	}

    public static function build_level( $persona, $options, $lvl ) {
        $block = '';
        $indent = ($lvl - 1) * 2;
        for($i = 0; $i < $indent; $i++) {
            $block .= '&nbsp;';
        }
        $block .= $lvl . '&nbsp;<a href="' . $options['home_url'] . '?page_id='
                . $persona->page . '">'
                . $persona->full_name . '</a>';


        if($options['hide_dates'] == 0 ) {
            $block .= '&nbsp;+&nbsp;<span style="font-size:smaller;">';
            $d = $persona->birth_date;
            if( isset( $d ) & !empty( $d ) ) {
                $block .= ' b: ' . $d;
            }
            $d = $persona->death_date;
            if( isset( $d ) & !empty( $d ) ) {
                $block .= ' d: ' . $d;
            }
            $block .= '</span>';
        }
        $block .= '<br/>';

        $cnt = count($persona->marriages);
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $marriage = $persona->marriages[$idx];
            if ( $marriage['spouse1']->id == $persona->id ) {
                $associated = $marriage['spouse2'];
            } else {
                $associated = $marriage['spouse1'];
            }
            if( isset( $associated ) && !empty( $associated ) ) {
                $block .= '&nbsp;&nbsp;+&nbsp;<a href="' . $options['home_url'] . '?page_id='
                . $associated->page . '">'
                . $associated->full_name . '</a>';
            }
            if($options['hide_dates'] == 0 ) {
                $block .= '&nbsp;+&nbsp;<span style="font-size:smaller;">';
                $d = $persona->birth_date;
                if( isset( $d ) & !empty( $d ) ) {
                    $block .= ' b: ' . $d;
                }
                $d = $persona->death_date;
                if( isset( $d ) & !empty( $d ) ) {
                    $block .= ' d: ' . $d;
                }
                $block .= '</span>';
            }

            $block .= '<br/>';
            // recurse children
        }
        return $block;
    }
}
?>
