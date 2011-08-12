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
        $block = "<div style='overflow:hidden;width:60%;margin:40px;'><form  action='" . $action . "' method='POST'>";
        $creator = new RP_Header_Panel_Creator();

        $block .= $creator->create_for_edit($persona, $options)
                . $this->create_privacy_panel($persona, $options);

        $creator = new RP_Picture_Panel_Creator();
        $block .= $creator->create_for_edit($persona, $options)
               . "<div style='float:right;padding-right:15px;'><input type='submit'"
                . " name='submitPersonForm' id='submitPersonForm' value='"
                . __( 'Submit', 'rootspersona' ) . "'/>"
                . "&#160;&#160;&#160;<input type='reset' name='reset' value='"
                . __( 'Reset', 'rootspersona' ) . "'/></div>"

                . RP_Persona_Helper::get_banner($options, '&#160;')
                . "<input type='hidden' name='srcPage' id='srcPage' value='" . $options['src_page'] . "'>"
                . "<input type='hidden' name='personId' id='personId' value='" . $persona->id . "'>"
                . "<input type='hidden' name='fullName' id='fullName' value='" . $persona->full_name . "'>"
                . "</div></div>"
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
                . '<div style="font-size:smaller;padding:10px;">'
                . '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
                . '<input type="radio" name="privacy_grp" value="Exc" ' . $exc . '>Exclude'
                . '</div><div style="float:left;width:35em;">'
                .  __( 'Delete page and exclude this person FROM future uploads.', 'rootspersona' )
                . '</div></div>'

                . '<div style="width:99%;clear:left;"><div style="width:9em;float:left;font-weight:bold;">'
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
     * @return string
     */
    function process_edit( $options ) {
        $p = $this->params_from_html( $_POST );
        $msg = '';
        if ( strlen( $p['personId'] ) < 1 ) $msg = $msg . "<br>" . __( 'Missing Id.', 'rootspersona' );
        if ( strlen( $p['srcPage'] ) < 1 ) $msg = $msg . "<br>" . __( 'Missing source page.', 'rootspersona' );
        $my_post = array();
        $my_post['ID'] = $p['srcPage'];
        $content = "[rootsPersona personId='" . $p['personId'] . "'";
        for ( $i = 1;$i <= 7;    $i++ ) {
            $pf = 'picFile' . $i;
            if ( isset( $p[$pf] )
            && ! empty( $p[$pf] ) ) {
                if( strstr($p[$pf], 'girl-silhouette.gif') === false
                        && strstr($p[$pf], 'boy-silhouette.gif') === false ) {

                    $content = $content . ' ' . $pf . "='" . $p[$pf] . "'";
                    $pc = 'picCap' . $i;
                    if ( isset( $p[$pc] )
                    && ! empty( $p[$pc] ) ) {
                        $content = $content . ' ' . $pc . "='" . $p[$pc] . "'";
                    }
                }
            }
        }
        $content = $content . "/]";
        $my_post['post_content'] = $content;
        wp_update_post( $my_post );

        return RP_Persona_Helper::redirect_to_page( $p['srcPage'] );
    }

    /**
     *
     * @param array $options
     * @return array
     */
    public function get_persona_options( $options ) {
        $options['home_url'] = home_url();
        $options['plugin_url'] = WP_PLUGIN_URL . '/rootspersona/';
        $options['action'] = $options['home_url'] . '/?page_id=' . RP_Persona_Helper::get_page_id();
        $options['uscore'] = RP_Persona_Helper::score_user();

        $options['hide_banner'] =  0;
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
