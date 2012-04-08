<?php

class RP_Facts_Panel_Creator {

    /**
     *
     * @param array $facts
     * @param array $options
     * @return string
     */
	public static function create( $facts, $options ) {
		$block = '<section class="rp_truncate">';
        $block .= RP_Persona_Helper::get_banner($options, __( 'Facts', 'rootspersona' ));
		$block .= '<div class="rp_facts">';
		$block .= '<ul>';
		$cnt = count( $facts );
		for ( $idx = 0; $idx < $cnt; $idx++ ) {
            if((!isset($facts[$idx]['classification'])
                    || empty($facts[$idx]['classification']))
               && ($options['hide_dates']
                    || !isset($facts[$idx]['date'])
                    || empty($facts[$idx]['date'])))
                continue;  // no real meaningfull data, so skip it
			$block .= '<li>';
			if ( ! $options['hide_dates']
			&& isset( $facts[$idx]['date'] )
			&& ! empty( $facts[$idx]['date'] ) ) {
				$block .= @preg_replace( '/@.*@(.*)/US', '$1', $facts[$idx]['date'] ) . ' - ';
			}
			$block .= $facts[$idx]['type'] . ' - ' . $facts[$idx]['classification'];
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
		$block .= '</ul></div></section>';
		return $block;
	}

    public static function create_for_edit( $facts, $options ) {
        $del = WP_PLUGIN_URL . '/rootspersona/images/delete-icon.png';

        $block = '<div class="rp_truncate">'
                . '<div class="rp_header" style="overflow:hidden;">'
                . "<input type='hidden' name='imgPath' id='imgPath' value='"
                . WP_PLUGIN_URL . "/rootspersona/images/'>";
        $block .= '<table style="margin:10px 5px;"><thead>'
                . '<tr><th>Fact/Event</th><th>Date</th><th>Place</th><th>Notes</th><th></th></tr>'
                . '</thead><tbody id="facts">';
		$cnt = count( $facts );
		for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $block .= '<tr>';
            //Fact
            $block .= '<td><input type="text" class="claimType" value="';
            if ( isset( $facts[$idx]['type'] )
			&& ! empty( $facts[$idx]['type'] ) ) {
				$block .= $facts[$idx]['type'];
			}
            $block .= '"/></td>';
            //Date
            $block .= '<td><input type="text" value="';
            if ( isset( $facts[$idx]['date'] )
			&& ! empty( $facts[$idx]['date'] ) ) {
				$block .= @preg_replace( '/@.*@(.*)/US', '$1', $facts[$idx]['date'] );
			}
            $block .= '"/></td>';
            //Place
            $block .= '<td><input type="text" value="';
            if ( isset( $facts[$idx]['place'] )
			&& ! empty( $facts[$idx]['place'] ) ) {
				$block .= $facts[$idx]['place'];
			}
            $block .= '"/></td>';
            //Notes
            $block .= '<td><textarea cols="30" rows="1" type="text">';
            if ( isset( $facts[$idx]['classification'] )
			&& ! empty( $facts[$idx]['classification'] ) ) {
				$block .= $facts[$idx]['classification'];
			}
            $block .= '</textarea>';
            $block .= '<td>'
                . '<img alt="Delete" src="' . $del . '" class="delFacts"/>'
                . '</td>';
            $block .= '</tr>';
        }
            $block .= '<tr>';
            //Fact
            $block .= '<td><input id="newclaim" type="text" class="claimType" value=""/></td>';
            //Date
            $block .= '<td><input id="newdate" type="text" value=""/></td>';
            //Place
            $block .= '<td><input id="newplace" type="text" value=""/></td>';
            //Notes
            $block .= '<td><textarea id="newclassification" type="text" cols="30" rows="1"/></textarea></td>';
            $block .= '<td id="newbutton">'
                . '</td>';
            $block .= '</tr>';
        $block .= '</tbody></table></div></div>';

        return $block;
    }
}
?>