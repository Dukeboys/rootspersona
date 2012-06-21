<?php

class RP_Picture_Panel_Creator {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    public static function create( $persona, $options ) {
        if ( ! isset($options['hide_undef_pics']) || $options['hide_undef_pics'] != '1') {
            $default = $options['plugin_url'] . '/images/boy-silhouette.gif';
            if ( isset( $persona->gender ) && $persona->gender == 'F' ) {
                $default = $options['plugin_url'] . '/images/girl-silhouette.gif';
            }
        }
        $block = '<section class="rp_truncate">'
               . RP_Persona_Helper::get_banner($options, __( 'Picture Gallery', 'rootspersona' ) )
               . '<div class="rp_pictures">';
        $cnt = 6;
        for ( $idx = 1;    $idx <= $cnt; $idx++ ) {
            if ( isset ( $persona->picfiles[$idx] )
                    || ! isset($options['hide_undef_pics'])
                    || $options['hide_undef_pics'] != '1') {
                $link = isset ( $persona->picfiles[$idx] ) ? $persona->picfiles[$idx] : $default;
                $block .= '<div class="rp_picture"><a href="' . $link
                        . '"><img width="100px" src="' . $link . '"/></a><div class="rp_caption">'
                        . ( isset( $persona->piccaps[$idx] ) ? $persona->piccaps[$idx] : '&#160;' )
                        . '</div></div>';
            }
        }

        $block .= '</div>'
                . RP_Persona_Helper::get_banner($options, '')
                . "</section>";
        return $block;
    }

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    public static function create_for_edit( $persona, $options ) {
        $default = $options['plugin_url'] . '/images/boy-silhouette.gif';
        if ( isset( $persona->gender ) && $persona->gender == 'F' ) {
            $default = $options['plugin_url'] . '/images/girl-silhouette.gif';
        }
        $block = '<div class="rp_truncate">'
            . RP_Persona_Helper::get_banner($options, 'Picture Gallery')
            . '<div class="rp_pictures">';

        $cnt = 6;
        for ( $idx = 1;  $idx <= $cnt; $idx++ ) {
            $link = isset ( $persona->picfiles[$idx] ) ? $persona->picfiles[$idx] : $default;
            $i = $idx + 1;
            $block .= '<div class="rp_picture" style="text-align:center;"><a href="' . $link
                    . '"><img id="img_' . $i . '" width="100px" src="' . $link . '"/></a>'
                    . '<input id="img_path_' . $i . '" type="hidden" name="img_path_' . $i . '" value="' . $link . '"/>'
                    . '<br/><input class="submitPersonForm"  id="img_' . $i . '_upload_button" type="button" value="Browse" />'

                    . '<div class="rp_caption"><textarea id="cap_' . $i . '" name="cap_' . $i . '" cols="12">'
                    . ( isset( $persona->piccaps[$idx] ) ? $persona->piccaps[$idx] : '&#160;' )
                    . '</textarea></div>'
                    . '</div>';
        }

        $block .= '</div>'
                //. RP_Persona_Helper::get_banner($options, '')
                . "</div>";
        return $block;
    }
}
?>
