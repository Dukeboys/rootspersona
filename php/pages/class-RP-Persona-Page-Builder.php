<?php
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Header-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Bio-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Facts-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Ancestors-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Picture-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Evidence-Panel-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/pages/panels/class-RP-Group-Sheet-Panel-Creator.php' );

class RP_Persona_Page_Builder {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @param integer $page_id
     * @return string
     */
    public function build( $persona, $options, $page_id ) {
        $block = '';
        if ( $options['hide_header'] == 0 ) {
            $block .= RP_Header_Panel_Creator::create( $persona, $options );
        }

        if ( $options['hide_bio'] == 0 && isset( $persona->notes ) && count( $persona->notes ) > 0) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Biography', 'rootspersona' ) );
            $block .= RP_Bio_Panel_Creator::create( $persona->notes, $options );
        }
        if ( $options['hide_facts'] == 0 && isset( $persona->facts ) && count( $persona->facts ) > 0) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Facts', 'rootspersona' ));
            $block .= RP_Facts_Panel_Creator::create( $persona->facts, $options );
        }
        if ( $options['hide_ancestors'] == 0 && isset( $persona->ancestors ) ) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Ancestors', 'rootspersona' ));
            $creator = new RP_Ancestors_Panel_Creator();
            $block .= $creator->create( $persona->ancestors, $options );
        }
        if ( $options['hide_family_c'] == 0  && isset( $persona->ancestors ) ) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Child', 'rootspersona' ));
            $block .= RP_Group_Sheet_Panel_Creator::create_group_child( $persona->ancestors, $persona->siblings, $options );
        }
        if ( $options['hide_family_s'] == 0 && isset( $persona->marriages )  && count( $persona->marriages ) > 0) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Family Group Sheet - Spouse', 'rootspersona' ));
            $cnt = count( $persona->marriages );
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $marriage = $persona->marriages[$idx];
                $block .= RP_Group_Sheet_Panel_Creator::create_group_spouse( $marriage, $options );
            }
        }
        if ( $options['hide_pictures'] == 0  && isset( $persona->picFiles )  && count( $persona->picFiles ) > 0) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Picture Gallery', 'rootspersona' ) );
            $block .= RP_Picture_Panel_Creator::create( $persona, $options );
        }
        if ( $options['hide_evidence'] == 0  && isset( $persona->sources )   && count( $persona->sources ) > 0) {
            $block .= RP_Persona_Helper::get_banner($options, __( 'Evidence', 'rootspersona' ) );
            $block .= RP_Evidence_Panel_Creator::create( $persona, $options );
        }
        $block .= RP_Persona_Helper::get_banner($options, '');
        $block .= RP_Persona_Page_Builder::create_end_of_page( $persona->id, $persona->batch_id, $page_id, $options );
        return $block;
    }

    /**
     *
     * @param array $atts
     * @param string $callback
     * @param array $options
     * @return array
     */
    public function get_persona_options( $atts, $callback, $options ) {
        $callback = strtolower( $callback );
        $options['home_url'] = home_url();
        $options['plugin_url'] = WP_PLUGIN_URL . '/rootspersona/';
        $options['uscore'] = RP_Persona_Helper::score_user();
        if ( empty( $callback )
        || $callback == 'rootspersona' ) {
            $options['hide_banner'] = 0;
        } else {
            $options['hide_header'] = 1;
            $options['hide_facts'] = 1;
            $options['hide_ancestors'] = 1;
            $options['hide_family_c'] = 1;
            $options['hide_family_s'] = 1;
            $options['hide_pictures'] = 1;
            $options['hide_evidence'] = 1;
            $options['hide_banner'] = 1;
            if ( $callback == 'rootspersonaheader' ) {
                $options['hide_header'] = 0;
            } else if ( $callback == 'rootspersonafacts' ) {
                $options['hide_facts'] = 0;
            } else if ( $callback == 'rootspersonaancestors' ) {
                $options['hide_ancestors'] = 0;
            } else if ( $callback == 'rootspersonafamilyc' ) {
                $options['hide_family_c'] = 0;
            } else if ( $callback == 'rootspersonafamilys' ) {
                $options['hide_family_s'] = 0;
            } else if ( $callback == 'rootspersonapictures' ) {
                $options['hide_pictures'] = 0;
            } else if ( $callback == 'rootspersonaevidence' ) {
                $options['hide_evidence'] = 0;
            }
        }

        for ( $idx = 1; $idx <= 7; $idx++ ) {
            $pic = 'picfile' . $idx ;
            if ( isset( $atts[$pic] ) ) {
                $options[$pic] = $atts[$pic];
                $cap = 'piccap' . $idx;
                if ( isset( $atts[$cap] ) ) {
                    $options[$cap] = $atts[$cap];
                }
            }
        }
        return $options;
    }

    /**
     *
     * @param string $id
     * @param integer $page_id
     * @param integer $options
     * @return string
     */
    public static function create_end_of_page( $id, $batch_id,  $page_id, $options ) {
        $block = '';
        if ( ( get_post_type( $page_id ) != 'post' )
        && ( current_user_can( "edit_pages" ) )
        && $options['hide_edit_links'] != 1 ) {
            $win1 = __( 'Page will be removed but supporting data will not be deleted.  Proceed?', 'rootspersona' );
            $win2 = __( 'Page will be removed and supporting data will be deleted.  Proceed?', 'rootspersona' );

            $edit_page = admin_url('/tools.php?page=rootsPersona&rootspage=edit')
                        . "&personId=" . $id . "&batchId=" . $batch_id
                        . "&srcPage=" . $page_id . "&action=";

            $block .= "<div style='margin-top:10px;text-align: center;'>"
                . "<span class='rp_linkbutton'  " . RP_Tools_Page_Builder::hover 
                . " style='width:100px'><a href='"
                . $edit_page . "edit'>" . __( 'Edit Person', 'rootspersona' )
                . "</a></span>";
            $url = $options['plugin_url'] . 'php/pages/';

            $block .= "&#160;&#160;<span class='rp_linkbutton' " . RP_Tools_Page_Builder::hover 
                . " style='width:120px'><a href='#'"
                . " onClick='javascript:rootsConfirm(\""
                . $win2 . "\",\"" . $edit_page
                . "delete\");return false;'>" . __( 'Delete Person', 'rootspersona' )
                . "</a></span>";

            $block .= "</div>";
        }
        return $block;
    }
}
?>
