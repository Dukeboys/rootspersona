<?php

class RP_Ancestors_Panel_Creator {

    /**
     *
     * @param array $ancestors
     * @param array $options
     * @return string
     */
	public static function create( $ancestors, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

        $isEdit = (($options['is_system_of_record'] == '1'?true:false) && current_user_can( "edit_pages" ));
        $fname = $ancestors[4]->full_name;
        if($isEdit && $fname == '?'  && $ancestors[2]->full_name != '?') {
            $fname = '+';
        }
		$block = '<section class="rp_truncate">'
                . RP_Persona_Helper::get_banner($options, __( 'Ancestors', 'rootspersona' ))
                . '<div class="rp_ancestors">'
                . '<table cellpadding="0" cellspacing="0" class="ancestors"><tbody>'
                . '<tr><td colspan="2" rowspan="6">&#160;</td>'
                . '<td colspan="3" rowspan="2">&#160;</td><td>&#160;</td>'
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[4], $options, $fname, $ancestors[2], 1, $ancestors[5]);

        $fname = $ancestors[2]->full_name;
        if($isEdit && $fname == '?' ) {
            $fname = '+';
        }

		$block .= '</td></tr>' . '<tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>'
                . '<tr><td>&#160;</td>'
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[2], $options, $fname, $ancestors[1], 1, $ancestors[3]);

        $fname = $ancestors[5]->full_name;
        if($isEdit && $fname == '?' && $ancestors[2]->full_name != '?') {
            $fname = '+';
        }

		$block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>'
                . '<tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td><td>&#160;</td></tr>'
                . '<tr><td colspan="3" rowspan="6" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td><td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td>'
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[5], $options, $fname, $ancestors[2], 2, $ancestors[4]);

        $block .= '</td></tr>'
                . '<tr><td>&#160;</td></tr><tr><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[1], $options, $ancestors[1]->full_name, null, null, null);

        $fname = $ancestors[6]->full_name;
        if($isEdit && $fname == '?' && $ancestors[3]->full_name != '?') {
            $fname = '+';
        }

        $block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2">&#160;</td></tr>'
                . '<tr><td>&#160;</td></tr>'
                . '<tr><td colspan="2" rowspan="6">&#160;</td><td>&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[6], $options, $fname, $ancestors[3], 1, $ancestors[7]);

        $fname = $ancestors[3]->full_name;
        if($isEdit && $fname == '?' ) {
            $fname = '+';
        }

        $block .= '</td></tr><tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>'
                . '<tr><td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[3], $options, $fname, $ancestors[1], 2, $ancestors[2]);

        $fname = $ancestors[7]->full_name;
        if($isEdit && $fname == '?' && $ancestors[3]->full_name != '?') {
            $fname = '+';
        }

		$block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>'
                . '<tr><td>&#160;</td><td>&#160;</td></tr><tr><td colspan="3" rowspan="2">&#160;</td>'
                . '<td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">'
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[7], $options, $fname, $ancestors[3], 2, $ancestors[6]);

		$block .= '</td></tr><tr><td>&#160;</td></tr>';
		$block .= '</tbody></table></div></section>';
		return $block;
	}

    public static function buildBlock($ancestor, $options, $full_name, $descendant, $sseq, $spouse) {
        $block = '<div class="nospace" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                . '<meta itemprop="gender" content="' . $ancestor->gender . '"/>';
        if ( $full_name != '?' && $full_name != '+' ) {
            $block .= '<a href="' . $options['home_url'] . '?page_id='
                    . $ancestor->page . '">';
        } else if ($full_name == '+') {
            $block .= '<a href="'
                    . admin_url('/tools.php?page=rootsPersona&rootspage=edit&action=edit&fams=')
                    . $descendant->famc . '&sseq=' . $sseq
                    . '&child=' . $descendant->id
                    . '&spouse=' . $spouse->id
                    . '">';
        }
        $block .= '<span class="nospace" id="anc' . $ancestor->id . '_name" itemprop="name">'
                . $full_name . '</span>';
        if ( $full_name != '?') {
            $block .= '</a>';
        }
        $block .= '<br/>';

		if ( ! $options['hide_dates'] && $full_name != '?' && $full_name != '+' ) {
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->birth_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">'
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'' )
                         . @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->birth_date )
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';

                $block .= $tmpDate;

                $d = @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->death_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">'
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'')
                         . @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->death_date )
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';

                $block .= ' - ' . $tmpDate;
		}
        $block .= '</div>';
        return $block;
    }
}


?>
