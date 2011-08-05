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
        $target_url = $options['home_url'] . "?page_id=" . $options['index_page'];
        $pagination = RP_Persona_Helper::build_pagination( $options['page_nbr'],
                $options['per_page'], $cnt, $target_url );
        $xofy_start = ( ( $options['page_nbr'] * $options['per_page'] )
                - $options['per_page'] + 1 );
        $xofy_end = $xofy_start + count( $index ) - 1;
        $xofy = "<div class='xofy'>Displaying "
                . $xofy_start . ' - ' . $xofy_end . "</div>";
        $hide_dates = $options['hide_dates'];
        $home_url = $options['home_url'];
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
    public function get_options( $options ) {
        global $wp_query;
        $options['page_nbr'] = isset( $wp_query->query_vars['rootsvar'] )
                ? $wp_query->query_vars['rootsvar'] : 1 ;

        $options['per_page'] = isset( $options['per_page'] )
                ? $options['per_page'] : 25;

        $options['home_url'] = home_url();
        $options['uscore'] = RP_Persona_Helper::score_user();
        return $options;
    }
}
?>
