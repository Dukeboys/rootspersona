<?php

class RP_Edit_Page_Builder {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @param string $msg
     * @return string
     */
    function build( $persona, $action, $options, $msg = '' ) {

        $isSOR = ($options['is_system_of_record'] == '1'?true:false);
        $block = "<div style='overflow:hidden;margin:20px;'>"
                . "<form id='editPersonaForm' action='" . $action . "' method='POST'>"
                . "<div  class='rp_banner' style='padding-right:15px;margin-bottom:15px;font-size:smaller;'>"
                . "<input type='button' name='submitPersonForm' class='submitPersonForm' value='"
                . __( 'Save', 'rootspersona' ) . "' onclick='updatePersona();'/>"
                . "&#160;&#160;&#160;<input type='button' class='submitPersonForm' name='cancel' value='"
                . __( 'Cancel', 'rootspersona' ) . "' onclick='gotoPersonaPage(\"" . $options['home_url'] . "\");'/>"
               . "&#160;&#160;&#160;<input type='button' class='submitPersonForm' name='new' value='"
                . __( 'New', 'rootspersona' ) . "' onclick='window.location=\""
                . $options['admin_url'] . "/tools.php?page=rootsPersona&rootspage=edit&action=edit\";'/></div>";

        $block .= "<div class='persona_msg'></div>";
        $creator = new RP_Header_Panel_Creator();
        $block .= $creator->create_for_edit($persona, $options);

        if($isSOR) {
           // if($options['header_style'] == '2') {
                $creator = new RP_Bio_Panel_Creator();
                $block .= $creator->create_for_edit($persona->notes, $options);
           // }
            $creator = new RP_Facts_Panel_Creator();
            $block .= RP_Persona_Helper::get_banner($options, 'Facts')
                    . $creator->create_for_edit($persona->facts, $options);

            $creator = new RP_Group_Sheet_Panel_Creator();
            $block .= RP_Persona_Helper::get_banner($options, 'Family Groups')
                    . $creator->create_for_edit($persona, $options);
        }

        $block .= $this->create_privacy_panel($persona, $options);

        $creator = new RP_Picture_Panel_Creator();
        $block .= $creator->create_for_edit($persona, $options);


        $block .= "<div class='persona_msg'></div>";

        $block .= "<div  class='rp_banner' style='padding-right:15px;font-size:smaller;'>"
                . "<input type='button' name='submitPersonForm' class='submitPersonForm' value='"
                . __( 'Save', 'rootspersona' ) . "' onclick='updatePersona();'/>"
                . "&#160;&#160;&#160;<input type='button' class='submitPersonForm' name='cancel' value='"
                . __( 'Cancel', 'rootspersona' ) . "' onclick='gotoPersonaPage(\"" . $options['home_url'] . "\");'/></div>"

                . "<input type='hidden' name='persona_page' id='persona_page' value='" . isset($options['src_page'])?$options['src_page']:'' . "'>"
                . "<input type='hidden' name='personId' id='personId' value='" . $persona->id . "'>"
                . "<input type='hidden' name='batchId' id='batchId' value='" . $persona->batch_id . "'>"
                . "<input type='hidden' name='fullName' id='fullName' value='" . $persona->full_name . "'>"
                . "<input type='hidden' name='imgPath' id='imgPath' value='" . WP_PLUGIN_URL . '/rootspersona/images' . "'>"
                //. "</div></div>"
                . "</form></div>";
        return $block;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    function create_privacy_panel( $persona, $options ) {
        $opt = $persona->privacy;
        $exc = '';
        $pvt = '';
        $mbr = '';
        $pub = '';
        $def = '';
        if ( isset( $opt ) ) {
            if ( $opt == 'Pub' ) {
                $pub = "checked";
            } else if ( $opt == 'Mbr' ) {
                $mbr = "checked";
            } else if ( $opt == 'Exc' ) {
                $exc = "checked";
            } else if ( $opt == 'Pvt' ) {
                $pvt = "checked";
            } else if ( $opt == 'Def' ) {
                $def = "checked";
            }
        } else {
            $def = "checked";
        }

        $block = RP_Persona_Helper::get_banner($options, 'Select Privacy Setting')
                . '<div class="rp_truncate">'
                . '<div class="rp_header" style="overflow:hidden;">'
                . '<div style="font-size:smaller;padding:10px;overflow:hidden">';

        if(isset($persona->id) && !empty($persona->id)) {
            // makes no sense to exclude a new person
            $block .= '<div style="width:99%;clear:left;;"><div style="width:9em;float:left;font-weight:bold;">'
                    . '<input type="radio" name="privacy_grp" value="Exc" ' . $exc . '>Exclude'
                    . '</div><div style="float:left;width:35em;">'
                    .  __( 'Delete page and exclude this person FROM future uploads.', 'rootspersona' )
                    . '</div></div>';
        }

        $block .= '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
                . '<input type="radio" name="privacy_grp" value="Pvt" ' . $pvt . '>Private'
                . '</div><div style="float:left;width:35em;">'
                . __( 'This person will only be visible to the admin role.', 'rootspersona' )
                . '</div></div>'

                . '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
                . '<input type="radio" name="privacy_grp" value="Mbr" ' . $mbr . '>Members Only'
                . '</div><div style="float:left;width:35em;">'
                . __( 'This person will only be visible to users who are logged in.', 'rootspersona' )
                . '</div></div>'

                . '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
                . '<input type="radio" name="privacy_grp" value="Pub" ' . $pub . '>Public'
                . '</div><div style="float:left;width:35em;">'
                .  __( 'This person will be visible to ANYONE, even if they are living.', 'rootspersona' )
                . '</div></div>'

                . '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
                . '<input type="radio" name="privacy_grp" value="Def" ' . $def . '>Default'
                . '</div><div style="float:left;width:35em;">'
                .  __( 'This person will be visible based on the privacy setting for living persons or the global default setting.', 'rootspersona' )
                . '</div></div></div></div></div>';
        return $block;
    }

    /**
     *
     * @param array $options
     * @return array
     */
    public function get_persona_options( $options ) {
        $options['home_url'] = home_url();
        $options['admin_url'] = admin_url();
        $options['plugin_url'] = WP_PLUGIN_URL . '/rootspersona/';
        $options['action'] = $options['home_url'] . '/?page_id=' . RP_Persona_Helper::get_page_id();
        $options['uscore'] = RP_Persona_Helper::score_user();

        $options['hide_banner'] =  0;
        if(isset($options['src_page']) && !empty($options['src_page'])) {
            $page = get_post( $options['src_page'] );
            $content = $page->post_content;
            for ( $i = 1; $i <= 7; $i++ ) {
                $pf = 'picFile' . $i;
                if ( preg_match( "/$pf/", $content ) ) {
                    $options[$pf] = @preg_replace(
                            '/.*?' . $pf . '=[\'|"](.*)[\'|"].*?/US'
                            , '$1'
                            , $content
                            );
                    $pc = 'picCap' . $i;

                    if ( preg_match( "/$pc/", $content ) ) {
                            $options[$pc] = @preg_replace(
                                    '/.*?' . $pc . '=[\'|"](.*)[\'|"].*?/US'
                                    , '$1'
                                    , $content
                                );
                    }
                }
            }
        }
        return $options;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    function params_from_html( $params ) {
        $p['personId'] = isset( $params['personId'] ) ? trim( esc_attr( $params['personId'] ) ) : '';
        $p['srcPage'] = isset( $params['srcPage'] ) ? trim( esc_attr( $params['srcPage'] ) ) : '';
        for ( $i = 1; $i <= 7; $i++ ) {
            $p['picFile' . $i] = isset( $params['img' . $i . '_upload'] ) ? trim( esc_attr( $params['img' . $i . '_upload'] ) ) : '';
            $p['picCap' . $i] = isset( $params['cap' . $i] ) ? trim( esc_attr( $params['cap' . $i] ) ) : '';
        }
        return $p;
    }
}
?>
