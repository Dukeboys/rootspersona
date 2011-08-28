<?php

class RP_Evidence_Page_Builder {

    /**
     *
     * @param RP_Evidence $evidence
     * @param array $options
     * @return string
     */
	function build( $evidence, $options ) {
		$block = '<div class="rp_source">'
            . '<h3>' . __( 'Source', 'rootspersona' ) . ':</h3>'
            . '<p>' . $evidence->source_title . '</p>';

		if ( isset( $evidence->citations ) && count( $evidence->citations ) > 0 ) {
			$hdr = false;
			$cnt = count( $evidence->citations );
            $block .= '<h3>' . __( 'Citations', 'rootspersona' ) . ':</h3><ul>';
			for ( $idx = 0;	$idx < $cnt; $idx++ ) {
				$cite = $evidence->citations[$idx]->source_page;
				$block .= '<li>' . $cite . '</li>';
			}
            $block .= '</ul>';
		}

		if ( isset( $evidence->persons ) && count( $evidence->persons ) > 0 ) {
			$block .= '<h3>' . __( 'Person(s) of Interest', 'rootspersona' )
                    . ':</h3>' . '<ul>';
			$cnt = count( $evidence->persons );
			for ( $idx = 0;	$idx < $cnt; $idx++ ) {
				$p = $evidence->persons[$idx];
				$block .= '<li><a href="' . $options['home_url']
                    . '?page_id=' . $p->page . '">'
                    . $p->surname . ', ' . $p->given
                    . ' <span style="font-size:smaller">'
                    . ( $options['hide_dates'] == 1 ? ' ' : ( '(' . $p->birth_date . ' - '
                    . $p->death_date . ')' ) )
                    . '</span></a></li>';
			}
			$block .= '</ul>';
		}

		if ( isset( $evidence->notes ) && count( $evidence->notes ) > 0 ) {
			$block .= '<h3>' . __( 'Notes', 'rootspersona' ) . ':</h3>' . '<div>';
			$cnt = count( $evidence->notes );
			for ( $idx = 0;	$idx < $cnt; $idx++ ) {
				$block .= '<p>' . nl2br( $evidence->notes[$idx]->note ) . '</p>';
			}
			$block .= '</div>';
		}
		$block .= '</div>';
		return $block;
	}

    /**
     *
     * @param array $sources
     * @param integer $cnt
     * @param array $options
     * @return string
     */
    function build_index( $sources, $cnt, $options ) {
        if( isset( $options['style'] ) && 'scrollable' == $options['style'] ) {
            return $this->build_scrollable( $sources, $options );
        } else {
            return $this->build_paginated( $sources, $cnt, $options );
        }
    }    
    
    function build_paginated( $sources, $cnt, $options ) {
        $target_url = $options['home_url'] . "?page_id="
            . RP_Persona_Helper::get_page_id();
        $pagination = RP_Persona_Helper::build_pagination( $options['page_nbr'],
                $options['per_page'], $cnt, $target_url );
        $xofy_start = ( ( $options['page_nbr'] * $options['per_page'] )
                            - $options['per_page'] + 1 );
        $xofy_end = $xofy_start + count( $sources ) - 1;
        $xofy = "<div class='xofy'>Displaying " . $xofy_start . ' - ' . $xofy_end . "</div>";
        $home_url = $options['home_url'];
        $hdrcolor = ((isset($options['index_hdr_color']) && !empty($options['index_hdr_color'])) 
                        ? $options['index_hdr_color'] : '#CCCCCC');
        $block = $pagination . $xofy;
        $block .= "<table id='personaIndexTable' cellpadding='0' cellspacing='0'>"
                . "<tr><th style='background-color:$hdrcolor' class='source_name'>Source Name</th>"
                . "<th style='background-color:$hdrcolor' class='page'>Link</th></tr>";
        $evenodd = 'even';
                $evencolor = ((isset($options['index_even_color']) && !empty($options['index_even_color'])) 
                        ? $options['index_even_color'] : 'white');
        $oddcolor = ((isset($options['index_odd_color']) && !empty($options['index_odd_color'])) 
                        ? $options['index_odd_color'] : '#DDDDDD');
        $evenoddcolor = $evencolor;
        if( count( $sources ) > 0 ) {
            foreach ( $sources AS $src ) {
                $block .= "<tr class='" . $evenodd . "'><td style='background-color:$evenoddcolor' class='surname'>"
                        . $src->title . "</td>"
                        . "<td style='background-color:$evenoddcolor' class='page'><a href='" . $home_url . "?page_id="
                        . $src->page . "'>" . $src->page . "</a>"
                        . "</td></tr>";
                $evenodd = ( $evenodd == 'even' ) ? 'odd' : 'even';
                $evenoddcolor = ( $evenodd == 'even' ) ? $evencolor : $oddcolor;
            }
        }
        $block .= '</table>' . $xofy . $pagination;
        return $block;
    }
    
    function build_scrollable( $index, $options ) {
        $rows = $options['per_page'];
        
        $block = "<div id='personaIndexTable' style='text-align:center'><form>"
                . "<select id='persona_page' name='persona_page' size='$rows' onChange='javascript:findPage(\""
                . $options['home_url'] ."\");'>";
        
        if( count( $index ) > 0 ) {
            foreach( $index AS $entry ) {
                $block .= "<option value='" . $entry->page . "'>"
                        . $entry->title
                        . "</option>";
            }
        }
        
        $block .= "</select></form></div>";
        return $block;
    }
    
    /**
     *
     * @global WP_Query $wp_query
     * @param array $options
     * @return array
     */
    public function get_options( $options, $atts ) {
        global $wp_query;
        $options['page_nbr'] = isset( $wp_query->query_vars['rootsvar'] )
                ? $wp_query->query_vars['rootsvar'] : 1 ;

        $options['per_page'] = isset( $options['per_page'] )
                ? $options['per_page'] : 25;
        $options['home_url'] = home_url();
        $options['uscore'] = RP_Persona_Helper::score_user();
        $options['style'] = isset( $atts['style'] )? $atts['style'] : 'paginated';  
        return $options;
    }
}
?>
