<?php

class RP_Group_Sheet_Panel_Creator {

    /**
     *
     * @param array $ancestors
     * @param array $children
     * @param array $options
     * @return string
     */
    public static function create_group_child($pfx, $ancestors, $children, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

        $block = '<section class="rp_truncate">'
                . RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Child', 'rootspersona' ))
                . '<div class="rp_family">'
                . '<table class="familygroup" style="border-color:' . $pframe_color . ' !important"'
                . ' itemscope itemtype="http://historical-data.org/HistoricalFamily.html"><tbody>'
               . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'p', $ancestors[2], $ancestors[4], $ancestors[5], $options )
               . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'm', $ancestors[3], $ancestors[6], $ancestors[7], $options )
               . RP_Group_Sheet_Panel_Creator::show_children($pfx . 'c', $children, $options )
               . '</tbody></table></div></section>';
        return $block;
    }

    /**
     *
     * @param array $marriage
     * @param array $options
     * @return string
     */
    public static function create_group_spouse($pfx, $marriage, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

        $block = '<section class="rp_truncate">'
            . RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Spouse', 'rootspersona' ))
            . '<div class="rp_family">'
            . '<table class="familygroup" style="border-color:' . $pframe_color . ' !important"'
            . ' itemscope itemtype="http://historical-data.org/HistoricalFamily.html"><tbody>'
            . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'p', $marriage['spouse1'],
                    $marriage['spouse1']->f_persona, $marriage['spouse1']->m_persona, $options )
            . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'm', $marriage['spouse2'],
                    $marriage['spouse2']->f_persona, $marriage['spouse2']->m_persona, $options );
        if (isset ( $marriage['children'] ) ) {
            $block .=  RP_Group_Sheet_Panel_Creator::show_children($pfx . 'c', $marriage['children'], $options );
        }
        $block .= '</tbody></table></div></section>';
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
    static function show_parent($pfx, $parent, $grandfather, $grandmother, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );
        $color = ( ( isset( $options['group_fcolor'] ) && ! empty( $options['group_fcolor'] ) )
                        ? $options['group_fcolor'] : 'black' );
        $bcolor = ( ( isset( $options['group_bcolor'] ) && ! empty( $options['group_bcolor'] ) )
                        ? $options['group_bcolor'] : null );
        $img = ( ( isset( $options['group_image'] ) && ! empty( $options['group_image'] ) )
                        ? $options['group_image'] : 'wp-content//plugins//rootspersona//images//familyGroupSidebar.jpg' );

        $fill = "color:$color;"
                . ( empty( $bcolor ) ?  "background-image:url('$img') !important;" : "background-image:none;background-color:$bcolor !important;" );

        $row_span = 4 + ( isset( $parent->marriages ) ?  count( $parent->marriages ) : 0 );
        $birth_date = isset( $parent->birth_date ) ? @preg_replace( '/@.*@(.*)/US', '$1', $parent->birth_date ): '';
        $birth_place = isset( $parent->birth_place ) ? $parent->birth_place : '';
        $death_date = isset( $parent->death_date ) ? @preg_replace( '/@.*@(.*)/US', '$1', $parent->death_date ) : '';
        $death_place = isset( $parent->death_place ) ? $parent->death_place : '';

        $ppfx = $pfx . $parent->id;
        $block = '<tr id="' . $ppfx . '" itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                . '<td class="full" colspan="4" style="' . $fill 
                . 'border-color:' . $pframe_color . ' !important">'
                . __( 'PARENT', 'rootspersona' )
                . ' (<span itemprop="gender">' . $parent->gender . '</span>) '
                . '<a href="' . $options['home_url'] . '?page_id='
                . $parent->page . '" itemprop="name">'
                . $parent->full_name . '</a>'
                . '<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_bdate ' . $ppfx . '_bloc"></span>'
                . '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_ddate ' . $ppfx . '_dloc"></span>'
                . '<span itemprop="marriages" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_mdate ' . $ppfx . '_mloc"></span>'
                . '<span itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson" itemref="' . $ppfx . '_father"></span>'
                . '<span itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson" itemref="' . $ppfx . '_mother"></span>'
                . '</td></tr>'

                . '<tr id="' . $ppfx . '_birth"><td class="inset" rowspan="' . $row_span . '" style="' . $fill 
                . 'border-color:' . $pframe_color . ' !important" >'
                . '<td class="label" style="border-color:' . $pframe_color . ' !important">'
                . __( 'Birth', 'rootspersona' ) . '</td>' 
                . '<td id="' . $ppfx . '_bdate" class="rp_date" style="border-color:' . $pframe_color . ' !important">'
                . ( $options['hide_dates'] == 1 ? '' : $birth_date ) . '</td>'
                . '<td id="' . $ppfx . '_bloc" itemprop="location" itemscope itemtype="http://schema.org/Place"'
                .' class="notes" style="border-color:' . $pframe_color . ' !important">' 
                . ( $options['hide_places'] == 1 ? '' : ( '<span itemprop="name">' . $birth_place . '</span>' ) )
                . '</td></tr>'

                . '<tr id="' . $ppfx . '_death"><td class="label" style="border-color:' . $pframe_color . ' !important">' 
                . __( 'Death', 'rootspersona' )
                . '</td><td id="' . $ppfx . '_ddate" class="rp_date" style="border-color:' . $pframe_color . ' !important">' 
                . ( $options['hide_dates'] == 1 ? '' : $death_date )
                . ' </td><td id="' . $ppfx . '_dloc" itemprop="location" itemscope itemtype="http://schema.org/Place"'
                . ' class="notes" style="border-color:' . $pframe_color . ' !important">' 
                . ( $options['hide_places'] == 1 ? '' : ( '<span itemprop="name">' . $death_place . '</span>' ) )
                . '</td></tr>'

                . RP_Group_Sheet_Panel_Creator::show_marriage($ppfx, $parent, $options )

                . '<tr id="' . $ppfx . '_father">'
                . '<td class="label" style="border-color:' . $pframe_color 
                . ' !important">' 
                . __( 'Father', 'rootspersona' )
                . '</td><td class="parent" colspan="2" style="border-color:' . $pframe_color . ' !important">' 
                . '<a href="' . $options['home_url'] . '?page_id=' . $grandfather->page . '" itemprop="name">'
                . $grandfather->full_name . '</a></td></tr>' 

                . '<tr id="' . $ppfx . '_mother">'
                . '<td class="label" style="border-color:' . $pframe_color 
                . ' !important">'
                . __( 'Mother', 'rootspersona' ) 
                . '</td><td class="parent" colspan="2" style="border-color:' . $pframe_color . ' !important">'
                . '<a href="' . $options['home_url'] . '?page_id=' . $grandmother->page . '" itemprop="name">'
                . $grandmother->full_name 
                . '</a></td></tr>';
        return $block;
    }

    /**
     *
     * @param array $children
     * @param array $options
     * @return string
     */
    static function show_children($pfx, $children, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );
        $color = ( ( isset( $options['group_fcolor'] ) && ! empty( $options['group_fcolor'] ) )
                        ? $options['group_fcolor'] : 'black' );
        $bcolor = ( ( isset( $options['group_bcolor'] ) && ! empty( $options['group_bcolor'] ) )
                        ? $options['group_bcolor'] : null );
        $img = ( ( isset( $options['group_image'] ) && ! empty( $options['group_image'] ) )
                        ? $options['group_image'] : 'wp-content//plugins//rootspersona//images//familyGroupSidebar.jpg' );

        $fill = "color:$color;"
                . ( empty( $bcolor ) ?  "background-image:url('$img') !important;" : "background-image:none;background-color:$bcolor !important;" );

        $block = '<tr><td class="full" colspan="4" style="' . $fill . 'border-color:' . $pframe_color . ' !important">'
        . __( 'CHILDREN', 'rootspersona' ) . '</td></tr>';
        $cnt = count( $children );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $ppfx = $pfx . $idx . $children[$idx]->id;
            $birth_date = isset( $children[$idx]->birth_date ) ? @preg_replace( '/@.*@(.*)/US', '$1', $children[$idx]->birth_date ) : '';
            $birth_place = isset( $children[$idx]->birth_place ) ? $children[$idx]->birth_place : '';
            $death_date = isset( $children[$idx]->death_date ) ? @preg_replace( '/@.*@(.*)/US', '$1', $children[$idx]->death_date ) : '';
            $death_place = isset( $children[$idx]->death_place ) ? $children[$idx]->death_place : '';

            $row_span = 2 + count( $children[$idx]->marriages );
            $block .= '<tr id="' . $ppfx . '" itemprop="children" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                    . '<td class="gender" style="' . $fill . 'border-color:' . $pframe_color . ' !important" itemprop="gender">'
                    . $children[$idx]->gender 
                    . '</td><td class="child" colspan="3" style="' . $fill . 'border-color:' . $pframe_color . ' !important">'
                    . '<a href="' . $options['home_url'] . '?page_id=' . $children[$idx]->page . '" itemprop="name">'
                    . $children[$idx]->full_name . '</a>'
                    . '<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_bdate ' . $ppfx . '_bloc"></span>'
                    . '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_ddate ' . $ppfx . '_dloc"></span>'
                    . '<span itemprop="marriages" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_mdate ' . $ppfx . '_mloc"></span>'
                
                    . '</td></tr>'
                    
            . '<tr id="' . $ppfx . '_birth">'
            . '<td class="inset" rowspan="' . $row_span . '" style="' . $fill . 'border-color:' . $pframe_color . ' !important"/>'
            . '<td class="label" style="border-color:' . $pframe_color . ' !important">'
            . __( 'Birth', 'rootspersona' ) . '</td>' 
            . '<td id="' . $ppfx . '_bdate" itemprop="startDate" class="rp_date" style="border-color:' . $pframe_color . ' !important">'
            . ( $options['hide_dates'] == 1 ? '' : $birth_date )
            . '</td><td id="' . $ppfx . '_bloc" itemprop="location" itemscope itemtype="http://schema.org/Place"'
            . ' class="notes" style="border-color:' . $pframe_color . ' !important">' 
            . '<span itemprop="name">' .  ( $options['hide_places'] == 1 ? '' : $birth_place )
            . '</span></td></tr>' 
                    
            . '<tr id="' . $ppfx . '_death"><td class="label" style="border-color:' . $pframe_color . ' !important">' 
            . __( 'Death', 'rootspersona' ) . '</td>'
            . '<td id="' . $ppfx . '_ddate" itemprop="startDate" class="rp_date" style="border-color:' . $pframe_color . ' !important">'
            . ( $options['hide_dates'] == 1 ? '' : $death_date ) . '</td>'
            . '<td id="' . $ppfx . '_dloc" itemprop="location" itemscope itemtype="http://schema.org/Place"'
                    . ' class="notes" style="border-color:' . $pframe_color . ' !important">'
            . '<span itemprop="name">' . ( $options['hide_places'] == 1 ? '' : $death_place ) . '</span></td></tr>'
            . RP_Group_Sheet_Panel_Creator::show_marriage($ppfx, $children[$idx], $options );
        }
        return $block;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    static function show_marriage($pfx, $persona, $options ) {
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) )
                        ? $options['pframe_color'] : 'brown' );

        $block = '';
        $cnt = ( isset( $persona->marriages ) ?  count( $persona->marriages ) : 0 );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $ppfx = $pfx . $idx;
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
                        . '?page_id=' . $associated->page 
                        . '" itemprop="attendees" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                        . '<span itemprop="name">' . $associated->full_name . '</span></a> ';
            }
            $place = '';
            if ( isset( $marriage['place'] )
            && ! empty( $marriage['place'] )
            && ! ( $options['hide_places'] == 1 ) ) {
                $place = ' ' . __( 'at', 'rootspersona' ) . ' ' . $marriage['place'];
            }
            $block .= '<tr id="' . $ppfx . '_marriage"><td class="label" style="border-color:' 
                    . $pframe_color . ' !important">' . __( 'Marriage', 'rootspersona' )
                    . '</td><td id="' . $ppfx . '_mdate" class="rp_date" style="border-color:' . $pframe_color . ' !important" itemprop="startDate">'
                    . ( $options['hide_dates'] == 1 ? '' : $marriage['date'] )
                    . '</td><td class="notes" style="border-color:' . $pframe_color . ' !important">' 
                    . $spouse . '<span  id="' . $ppfx . '_mloc" itemprop="location" itemscope itemtype="http://schema.org/Place">'
                    . '<span itemprop="name">' . $place . '</span></span></td></tr>';
        }
        return $block;
    }
}
?>