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

		$block = '<section class="rp_truncate">'
               . RP_Persona_Helper::get_banner($options, __( 'Descendancy Chart', 'rootspersona' ))
               . '<div class="rp_descendants" style="padding:10px 4px;">'
               . RP_Descendancy_Panel_Creator::build_level( $persona, $options, 1 )
		       . '</div></section>';
		return $block;
	}

    public static function build_level( $persona, $options, $lvl ) {
        $spacer1 = '';
        $indent1 = ($lvl - 1) * 2;
        $indent2 = $indent1 + 1;

        $block = '<div style="padding-left:' . $indent1 . 'em;">' . $lvl . '&nbsp;<a href="' . $options['home_url'] . '?page_id='
                . $persona->page . '">'
                . $persona->full_name . '</a>';


        if($options['hide_dates'] == 0 ) {
            $block .= '<span style="font-size:smaller;padding-left:1em;">';
            $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->birth_date );
            if( isset( $d ) & !empty( $d ) ) {
                $block .= ' b: ' . $d;
            }
            $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->death_date );
            if( isset( $d ) & !empty( $d ) ) {
                $block .= ' d: ' . $d;
            }
            $block .= '</span>';
        }
        $block .= '</div>';
        
        $cnt = count($persona->marriages);
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $marriage = $persona->marriages[$idx];
            if ( $marriage['spouse1']->id == $persona->id ) {
                $associated = $marriage['spouse2'];
            } else {
                $associated = $marriage['spouse1'];
            }

            if( isset( $associated ) && !empty( $associated ) ) {
                $block .= '<div style="padding-left:' . $indent2 . 'em;">' 
                . '+&nbsp;<a href="' . $options['home_url'] . '?page_id='
                . $associated->page . '">'
                . $associated->full_name . '</a>';
            }
            if($options['hide_dates'] == 0 ) {
                $block .= '<span style="font-size:smaller;padding-left:1em;">';
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $associated->birth_date );
                if( isset( $d ) & !empty( $d ) ) {
                    $block .= ' b: ' . $d;
                }
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $associated->death_date );
                if( isset( $d ) & !empty( $d ) ) {
                    $block .= ' d: ' . $d;
                }
                $block .= '</span>';
            }

            $block .= '</div>';
            if($lvl < 10) {
                $cnt2 = count($marriage['children']);
                // recurse children
               for ( $idx2 = 0; $idx2 < $cnt2; $idx2++ ) {
                    $child = $marriage['children'][$idx2];
                    $block .= RP_Descendancy_Panel_Creator::build_level( $child, $options, $lvl + 1 );
               }
            }
        }
        return $block;
    }
}
?>
