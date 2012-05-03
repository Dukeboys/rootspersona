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

        $famc = $ancestors[1]->famc;

        $block = '<section class="rp_truncate">'
                . RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Child', 'rootspersona' ))
                . '<div class="rp_family">'
                . '<table class="familygroup" style="border-color:' . $pframe_color . ' !important"'
                . ' itemscope itemtype="http://historical-data.org/HistoricalFamily.html"><tbody>'
               . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'p', $ancestors[2], $ancestors[4], $ancestors[5], $options, $famc, 1 )
               . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'm', $ancestors[3], $ancestors[6], $ancestors[7], $options, $famc, 2 )
               . RP_Group_Sheet_Panel_Creator::show_children($pfx . 'c', $children, $options, $famc )
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

        $fams =  $marriage['fams'];
        $block = '<section class="rp_truncate">'
            . RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Spouse', 'rootspersona' ))
            . '<div class="rp_family">'
            . '<table class="familygroup" style="border-color:' . $pframe_color . ' !important"'
            . ' itemscope itemtype="http://historical-data.org/HistoricalFamily.html"><tbody>'
            . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'p', $marriage['spouse1'],
                    $marriage['spouse1']->f_persona, $marriage['spouse1']->m_persona, $options, $fams, 1 )
            . RP_Group_Sheet_Panel_Creator::show_parent($pfx . 'm', $marriage['spouse2'],
                    $marriage['spouse2']->f_persona, $marriage['spouse2']->m_persona, $options, $fams, 2 );
        if (isset ( $marriage['children'] ) ) {
            $block .=  RP_Group_Sheet_Panel_Creator::show_children($pfx . 'c', $marriage['children'], $options,$fams );
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
    static function show_parent($pfx, $parent, $grandfather, $grandmother, $options, $fams, $sseq ) {
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
        $isEdit = (($options['is_system_of_record'] == '1'?true:false) && current_user_can( "edit_pages" ));
        $fname = $parent->full_name;
        $a = '';
        if($isEdit && $fname == '?') {
            $a = '<a href="'
                    . admin_url('/tools.php?page=rootsPersona&rootspage=edit&action=edit&fams=')
                    . $fams . '&sseq=' . $sseq
                    . '">+</a>';
        } else if ($fname == '?') {
            $a = $fname;
        } else {
            $a = '<a href="' . $options['home_url'] . '?page_id='
                . $parent->page . '" itemprop="name">'
                . $fname . '</a>';
        }

        $block = '<tr id="' . $ppfx . '" itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                . '<td class="full" colspan="4" style="' . $fill
                . 'border-color:' . $pframe_color . ' !important">'
                . __( 'PARENT', 'rootspersona' )
                . ' (<span itemprop="gender">' . $parent->gender . '</span>) '
                . $a
                .'<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_bdate ' . $ppfx . '_bloc"></span>'
                . '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_ddate ' . $ppfx . '_dloc"></span>'
                . '<span itemprop="marriages" itemscope itemtype="http://historical-data.org/HistoricalEvent" itemref="' . $ppfx . '_mdate ' . $ppfx . '_mloc"></span>'
                . '<span itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson" itemref="' . $ppfx . '_father"></span>'
                . '<span itemprop="parents" itemscope itemtype="http://historical-data.org/HistoricalPerson" itemref="' . $ppfx . '_mother"></span>'
                . '</td></tr>'

                . '<tr id="' . $ppfx . '_birth"><td class="inset" rowspan="' . $row_span . '" style="' . $fill
                . 'border-color:' . $pframe_color . ' !important" >'
                . '<td class="label" style="border-color:' . $pframe_color . ' !important">'
                . __( 'Birth', 'rootspersona' ) . '</td>'
                . '<td id="' . $ppfx . '_bdate" itemprop="startDate" class="rp_date" style="border-color:' . $pframe_color . ' !important">'
                . ( $options['hide_dates'] == 1 ? '' : $birth_date ) . '</td>'
                . '<td id="' . $ppfx . '_bloc" itemprop="location" itemscope itemtype="http://schema.org/Place"'
                .' class="notes" style="border-color:' . $pframe_color . ' !important">'
                . ( $options['hide_places'] == 1 ? '' : ( '<span itemprop="name">' . $birth_place . '</span>' ) )
                . '</td></tr>'

                . '<tr id="' . $ppfx . '_death"><td class="label" style="border-color:' . $pframe_color . ' !important">'
                . __( 'Death', 'rootspersona' )
                . '</td><td id="' . $ppfx . '_ddate" itemprop="startDate" class="rp_date" style="border-color:' . $pframe_color . ' !important">'
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
    static function show_children($pfx, $children, $options, $famc ) {
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

        $isEdit = (($options['is_system_of_record'] == '1'?true:false) && current_user_can( "edit_pages" ));
        $a = '';
        if($isEdit) {
            $a = '<span style="float:right;"><a href="'
                    . admin_url('/tools.php?page=rootsPersona&rootspage=edit&action=edit&famc=')
                    . $famc
                    . '">+</a></span>';
        }

        $block = '<tr><td class="full" colspan="4" style="' . $fill . 'border-color:' . $pframe_color . ' !important">'
                . __( 'CHILDREN', 'rootspersona' ) . $a . '</td></tr>';
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

    public static function create_for_edit( $persona, $options ) {
        $father = '';
        $mother = '';
        $fid = '';
        $mid = '';
        if(isset($persona->ancestors)) {
            if( isset($persona->ancestors[2])) {
                $father = $persona->ancestors[2]->full_name;
                $fid = $persona->ancestors[2]->id;
            }
            if( isset($persona->ancestors[3])) {
                $mother = $persona->ancestors[3]->full_name;
                $mid = $persona->ancestors[2]->id;
            }
        }
        $block = '<div class="rp_truncate">'
                . '<div class="rp_header" style="overflow:hidden;margin:10px;">';

        if(isset($persona->ancestors[2]) || isset($persona->ancestors[3])) {
            $u = '';
            $l = 'style="display:none;"';
        } else {
            $u = 'style="display:none;"';
            $l = '';
        }

        $block .= '<div><span style="font-weight:bold;font-size:14px;display:inline-block;width:21em;">Parents:</span>'
                . '<input id="rp_unlink_parents" name="rp_unlink_parents" class="submitPersonForm" type="button" onclick="unlinkparents(\'rp_famc\');" value="'
                . sprintf ( __( 'Unlink %s from this Father/Mother Family Group',
                'rootspersona' ), $persona->full_name ) . '" ' . $u . '>'
                . '<input id="rp_link_parents" name="rp_link_parents" class="submitPersonForm" type="button" onclick="linkparents();" value="'
                . sprintf ( __( 'Link %s to a Father/Mother',
                'rootspersona' ), $persona->full_name ) . '" ' . $l . '></div>'
                . '<input type="hidden" id="rp_famc" name="rp_famc" value="' . $persona->famc . '">'
                . '<div style="margin-left:10px;"><span style="font-weight:bold;font-style:italic;display:inline-block;width:5em;">Father: </span>'
                . '<span id="rp_father">' . $father . '</span></div>'
                . '<div style="margin-left:10px;"><span style="font-weight:bold;font-style:italic;display:inline-block;width:5em;">Mother: </span>'
                . '<span id="rp_mother">' . $mother . '</span></div>';

        $cnt = ( isset( $persona->marriages ) ?  count( $persona->marriages ) : 0 );
        if($cnt > 0) {
            $block .= '<div style="margin-top:10px;">'
                . '<span style="font-weight:bold;font-size:14px;">Family Groups:</span></div>';
        }
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $marriage = $persona->marriages[$idx];

            $associated = '';
            $sid = '';
            $sseq = '';
            if ( $marriage['spouse1']->id == $persona->id ) {
                $associated = $marriage['spouse2']->full_name;
                $sid = $marriage['spouse2']->id;
                $sseq = 2;
            } else {
                $associated = $marriage['spouse1']->full_name;
                $sid = $marriage['spouse1']->id;
                $sseq = 1;
            }
            $block .= '<div id="rp_group_' . $idx . '" name="rp_group_' . $idx . '">'
                   . '<div style="margin-left:10px;"><span style="display:inline-block;width:23.5em;">Family ' . $marriage['fams'] . '</span>'
                   . '<input type="hidden" id="rp_sseq_' . $idx . '" name="rp_sseq_' . $idx . '" value="' . $sseq . '">'
                   . '<input type="hidden" id="rp_sid_' . $idx . '" name="rp_sid_' . $idx . '" value="' . $sid . '">'
                   . '<input type="hidden" id="rp_fams_' . $idx . '" name="rp_fams_' . $idx . '" value="' . $marriage['fams'] . '">'
                   . '<input id="rp_fams_unlink_' . $idx . '" name="rp_fams_unlink_' . $idx . '" class="submitPersonForm" type="button" onclick="unlinkspouse(\'rp_fams_' . $idx . '\');"  value="'
                   . sprintf ( __( 'Unlink %s from this Family Group', 'rootspersona' ), $persona->full_name ) . '"></div>'
                   . '<div style="margin-left:20px;"><span style="font-weight:bold;font-style:italic;display:inline-block;width:5em;">Spouse: </span>' . $associated . '</div>';
            $cnt2 = count( $marriage['children'] );
            if($cnt2 > 0) {
                $block .= '<div style="margin-left:20px;font-weight:bold;font-style:italic;display:inline-block;width:5em;">Children: </div>';
                for ( $idx2 = 0; $idx2 < $cnt2; $idx2++ ) {
                    $child = $marriage['children'][$idx2];
                    $child->full_name;
                    $block .= '<div style="margin-left:40px;">' . $child->full_name . '</div>';
                }
            }
            $block .= '</div>';
        }

        $block .= '<div><span style="display:inline-block;width:24em;">&#160;</span>'
               . '<input class="submitPersonForm" type="button" onclick="linkspouse();"  value="'
               . sprintf ( __( 'Link %s to a Spouse/Family Group', 'rootspersona' ), $persona->full_name ) . '"></div>';
        $block .= '</div></div>';
        return $block;
    }
}
?>