<?php

class RP_Index_Page_Builder {

    /**
     *
     * @param array $index
     * @param integer $cnt
     * @param array $options
     * @return string
     */
    function build( $index, $cnt, $options ) {
        if( isset( $options['style'] ) && 'scrollable' == $options['style'] ) {
            return $this->build_scrollable( $index, $options );
        } else {
            return $this->build_paginated( $index, $cnt, $options );
        }
    }
    
    function build_scrollable( $index, $options ) {
        $hide_dates = $options['hide_dates'];
        $rows = $options['per_page'];
        
        $block = "<div id='personaIndexTable' style='text-align:center'><form>"
                . "<select id='persona_page' name='persona_page' size='$rows' onChange='javascript:findPage(\""
                . $options['home_url'] ."\");'>";
        
        foreach($index AS $entry) {
            $block .= "<option value='" . $entry->page . "'>"
                    . $entry->surname . ', ' . $entry->given
                    . ( $hide_dates == 1 ? ' ' : ( '&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;(' 
                    . $entry->birth_date . ' - '
                    . $entry->death_date ) . ')' )
                    . "</option>";
        }
        
        $block .= "</select></form></div>";
        return $block;
    }

    function build_paginated( $index, $cnt, $options ) {
        $home_url = $options['home_url'];
        $target_url = $home_url . "?page_id=" . RP_Persona_Helper::get_page_id();
        $pagination = RP_Persona_Helper::build_pagination( $options['page_nbr'],
                $options['per_page'], $cnt, $target_url );
        $xofy_start = ( ( $options['page_nbr'] * $options['per_page'] )
                - $options['per_page'] + 1 );
        $xofy_end = $xofy_start + count( $index ) - 1;
        $xofy = "<div class='xofy'>Displaying "
                . $xofy_start . ' - ' . $xofy_end . "</div>";
        $hide_dates = $options['hide_dates'];

        $block = $pagination . $xofy;
        $block .= "<table id='personaIndexTable' cellpadding='0' cellspacing='0'>"
                . "<tr><th class='surname'>Surname</th>"
                . "<th class='given'>Name</th><th class='dates'>Dates</th>"
                . "<th class='page'>Link</th></tr>";
        $evenodd = 'even';
        foreach ( $index AS $persona ) {
            $block .= "<tr class='" . $evenodd . "'><td class='surname'>"
                    . $persona->surname . "</td><td class='given'>"
                    . $persona->given . "</td><td class='dates'>"
                    . ( $hide_dates == 1 ? ' ' : ( $persona->birth_date . ' - '
                    . $persona->death_date ) )
                    . "</td><td class='page'><a href='" . $home_url . "?page_id="
                    . $persona->page . "'>" . $persona->page . "</a>"
                    . "</td></tr>";
            $evenodd = ( $evenodd == 'even' ) ? 'odd' : 'even';
        }
        $block .= '</table>' . $xofy . $pagination;
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
        $options['surname'] = isset( $atts['surname'] )? $atts['surname'] : null;
        $options['style'] = isset( $atts['style'] )? $atts['style'] : 'paginated';  
        return $options;
    }
}
?>
