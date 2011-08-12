<?php

class RP_Group_Sheet_Panel_Creator {

    /**
     *
     * @param array $ancestors
     * @param array $children
     * @param array $options
     * @return string
     */
    public static function create_group_child( $ancestors, $children, $options ) {
        $block = '<div class="rp_truncate">' . '<div class="rp_family"><table class="familygroup"><tbody>'
               . RP_Group_Sheet_Panel_Creator::show_parent( $ancestors[2], $ancestors[4], $ancestors[5], $options )
               . RP_Group_Sheet_Panel_Creator::show_parent( $ancestors[3], $ancestors[6], $ancestors[7], $options )
               . RP_Group_Sheet_Panel_Creator::show_children( $children, $options )
               . '</tbody></table></div></div>';
        return $block;
    }

    /**
     *
     * @param array $marriage
     * @param array $options
     * @return string
     */
    public static function create_group_spouse( $marriage, $options ) {
        $block = '<div class="rp_truncate">'
            . '<div class="rp_family"><table class="familygroup"><tbody>'
            . RP_Group_Sheet_Panel_Creator::show_parent( $marriage['spouse1'],
                    $marriage['spouse1']->f_persona, $marriage['spouse1']->m_persona, $options )
            . RP_Group_Sheet_Panel_Creator::show_parent( $marriage['spouse2'],
                    $marriage['spouse2']->f_persona, $marriage['spouse2']->m_persona, $options );
        if (isset ( $marriage['children'] ) ) {
            $block .=  RP_Group_Sheet_Panel_Creator::show_children( $marriage['children'], $options );
        }
        $block .= '</tbody></table></div></div>';
        return $block;
    }

    /**
     *
     * @param RP_Persona $parent
     * @param RP_Persona $grandfather
     * @param RP_Persona $grandmother
     * @param array $options
     * @return string
     */
    static function show_parent( $parent, $grandfather, $grandmother, $options ) {
        $row_span = 4 + ( isset( $parent->marriages ) ?  count( $parent->marriages ) : 0 );
        $birth_date = isset( $parent->birth_date ) ? $parent->birth_date : '';
        $birth_place = isset( $parent->birth_place ) ? $parent->birth_place : '';
        $death_date = isset( $parent->death_date ) ? $parent->death_date : '';
        $death_place = isset( $parent->death_place ) ? $parent->death_place : '';

        $block = '<tr><td class="full" colspan="4">'
                . __( 'PARENT', 'rootspersona' )
                . ' (' . $parent->gender . ') '
                . '<a href="' . $options['home_url'] . '?page_id='
                . $parent->page . '">'
                . $parent->full_name . '</a></td></tr>'

        . '<tr><td class="inset" rowspan="' . $row_span . '"/><td class="label">'
        . __( 'Birth', 'rootspersona' ) . '</td>' . '<td class="date">'
        . ( $options['hide_dates'] == 1 ? '' : $birth_date ) . '</td>'
        . '<td class="notes">' . ( $options['hide_places'] == 1 ? '' : $birth_place )
        . '</td></tr>' . '<tr><td class="label">' . __( 'Death', 'rootspersona' )
        . '</td><td class="date">' . ( $options['hide_dates'] == 1 ? '' : $death_date )
        . ' </td><td class="notes">' . ( $options['hide_places'] == 1 ? '' : $death_place )
        . '</td></tr>'
        . RP_Group_Sheet_Panel_Creator::show_marriage( $parent, $options )
        . '<tr><td class="label">' . __( 'Father', 'rootspersona' )
        . '</td><td class="parent" colspan="2">' . '<a href="'
        . $options['home_url'] . '?page_id=' . $grandfather->page . '">'
        . $grandfather->full_name . '</a></td></tr>' . '<tr><td class="label">'
        . __( 'Mother', 'rootspersona' ) . '</td><td class="parent" colspan="2">'
        . '<a href="' . $options['home_url'] . '?page_id=' . $grandmother->page . '">'
        . $grandmother->full_name . '</a></td></tr>';
        return $block;
    }

    /**
     *
     * @param array $children
     * @param array $options
     * @return string
     */
    static function show_children( $children, $options ) {
        $block = '<tr><td class="full" colspan="4">'
        . __( 'CHILDREN', 'rootspersona' ) . '</td></tr>';
        $cnt = count( $children );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $birth_date = isset( $children[$idx]->birth_date ) ? $children[$idx]->birth_date : '';
            $birth_place = isset( $children[$idx]->birth_place ) ? $children[$idx]->birth_place : '';
            $death_date = isset( $children[$idx]->death_date ) ? $children[$idx]->death_date : '';
            $death_place = isset( $children[$idx]->death_place ) ? $children[$idx]->death_place : '';

            $row_span = 2 + count( $children[$idx]->marriages );
            $block .= '<tr><td class="gender">' . $children[$idx]->gender . '</td><td class="child" colspan="3">'
            . '<a href="' . $options['home_url'] . '?page_id=' . $children[$idx]->page . '">'
            . $children[$idx]->full_name . '</a></td></tr>'
            . '<tr><td class="inset" rowspan="' . $row_span . '"/><td class="label">'
            . __( 'Birth', 'rootspersona' ) . '</td><td class="date">'
            . ( $options['hide_dates'] == 1 ? '' : $birth_date )
            . '</td><td class="notes">' . ( $options['hide_places'] == 1 ? '' : $birth_place )
            . '</td></tr>' . '<tr><td class="label">' . __( 'Death', 'rootspersona' ) . '</td><td class="date">'
            . ( $options['hide_dates'] == 1 ? '' : $death_date ) . '</td><td class="notes">'
            . ( $options['hide_places'] == 1 ? '' : $death_place ) . '</td></tr>'
            . RP_Group_Sheet_Panel_Creator::show_marriage( $children[$idx], $options );
        }
        return $block;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    static function show_marriage( $persona, $options ) {
        $block = '';
        $cnt = ( isset( $persona->marriages ) ?  count( $persona->marriages ) : 0 );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $marriage = $persona->marriages[$idx];
            $associated = null;
            if ( $marriage['spouse1']->id == $persona->id ) {
                $associated = $marriage['spouse2'];
            } else {
                $associated = $marriage['spouse1'];
            }
            $spouse = '';
            if ( isset( $associated )
            && ! empty( $associated->full_name ) ) {
                $spouse = __( 'to' )
                        . ' <a href="' . $options['home_url']
                        . '?page_id=' . $associated->page . '">'
                        . $associated->full_name . '</a> ';
            }
            $place = '';
            if ( isset( $marriage['place'] )
            && ! empty( $marriage['place'] )
            && ! ( $options['hide_places'] == 1 ) ) {
                $place = ' ' . __( 'at', 'rootspersona' ) . ' ' . $marriage['place'];
            }
            $block .= '<tr><td class="label">' . __( 'Marriage', 'rootspersona' )
                    . '</td><td class="date">'
                    . ( $options['hide_dates'] == 1 ? '' : $marriage['date'] )
                    . '</td><td class="notes">' . $spouse . $place . '</td></tr>';
        }
        return $block;
    }
}
?>
