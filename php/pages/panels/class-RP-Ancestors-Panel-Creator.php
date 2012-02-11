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
        
		$block = '<section class="rp_truncate">' 
                . RP_Persona_Helper::get_banner($options, __( 'Ancestors', 'rootspersona' ))
                . '<div class="rp_ancestors">' 
                . '<table cellpadding="0" cellspacing="0" class="ancestors"><tbody>' 
                . '<tr><td colspan="2" rowspan="6">&#160;</td>' 
                . '<td colspan="3" rowspan="2">&#160;</td><td>&#160;</td>' 
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[4], $options);
                
		$block .= '</td></tr>' . '<tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>' 
                . '<tr><td>&#160;</td>' 
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[2], $options);
        
		$block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>'
                . '<tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td><td>&#160;</td></tr>' 
                . '<tr><td colspan="3" rowspan="6" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td><td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td>' 
                . '<td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[5], $options);
		
        $block .= '</td></tr>' 
                . '<tr><td>&#160;</td></tr><tr><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[1], $options);
		
        $block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2">&#160;</td></tr>' 
                . '<tr><td>&#160;</td></tr>'
                . '<tr><td colspan="2" rowspan="6">&#160;</td><td>&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[6], $options);
		
        $block .= '</td></tr><tr><td class="rp_topleft" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>' 
                . '<tr><td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[3], $options);
        
		$block .= '</td><td class="rp_bottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td colspan="2" rowspan="2" class="rp_left" style="border-color:' . $pframe_color . ' !important">&#160;</td></tr>' 
                . '<tr><td>&#160;</td><td>&#160;</td></tr><tr><td colspan="3" rowspan="2">&#160;</td>' 
                . '<td class="rp_leftbottom" style="border-color:' . $pframe_color . ' !important">&#160;</td><td rowspan="2" class="rp_nameBox" style="border-color:' . $pframe_color . ' !important">' 
                . RP_Ancestors_Panel_Creator::buildBlock($ancestors[7], $options);
        
		$block .= '</td></tr><tr><td>&#160;</td></tr>';
		$block .= '</tbody></table></div></section>';
		return $block;
	}

    public static function buildBlock($ancestor, $options) {
        $block = '<div class="nospace" itemscope itemtype ="http://historical-data.org/HistoricalPerson">'
                . '<meta itemprop="gender" content="' . $ancestor->gender . '"/>'
                . '<span  class="nospace" itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent.html"' 
                . ' itemref="anc' . $ancestor->id . '_birth_date"></span>'
                . '<span  class="nospace" itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent.html"' 
                . ' itemref="anc' . $ancestor->id . '_death_date"></span>';
        $block .= '<a href="' . $options['home_url'] . '?page_id=' 
                . $ancestor->page . '">' 
                . '<span class="nospace" id="anc' . $ancestor->id . '_name" itemprop="name">' 
                . $ancestor->full_name . '</span>' 
                . '</a><br/>';
        
		if ( ! $options['hide_dates'] ) {
			$block .= '<span class="nospace" id="anc' . $ancestor->id . '_birth_date">' 
                   . @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->birth_date ) 
                   . '</span> - <span  class="nospace" id="anc' . $ancestor->id . '_death_date">' 
                   . @preg_replace( '/@.*@(.*)/US', '$1', $ancestor->death_date )
                   . '</span>';
		}      
        $block .= '</div>';
        return $block;
    }
}


?>
