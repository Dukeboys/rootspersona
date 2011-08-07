<?php

class RP_Header_Panel_Creator {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    public static function create( $persona, $options ) {
        $default = '';
        if ( ! isset($persona->picFiles[0])) {
            if( isset( $persona->gender )
            && $persona->gender == 'F' ) {
                $default = $options['plugin_url'] . "/images/girl-silhouette.gif";
            } else {
                $default = $options['plugin_url'] . "/images/boy-silhouette.gif";
            }
        } else {
            $default = $persona->picFiles[0];
        }
        $block = '<div class="rp_truncate">' . '<div class="rp_header">';
        $cnt = count( $persona->notes );
        if( ! isset ( $options['header_style'] )  || $options['header_style'] == '1' || $cnt == 0 ) {
            // original style
            $block .= '<a href="' . $default . '">'
            . '<img class="rp_headerbox" src="' . $default . '"/></a>'
            . '<div class="rp_headerbox">' . '<span class="rp_headerbox">'
            . $persona->full_name . '</span>'
            . '<span class="rp_headerbox" style="padding-left:15px;align:right;color:#EBDDE2">'
            . $persona->id . '</span>';
            if ( ! $options['hide_dates'] ) {
                $block .= '<br/>b: ' . $persona->birth_date
                        . '<br/>d: ' . $persona->death_date;
            }
            $block .= '</div>';
        } else if ( $options['header_style'] == '2' ) {
            // bio style
            $block .= '<a href="' . $default . '">'
                    . '<img class="rp_headerbox" style="margin:0px 30px 0px 0px !important;width:150px !important;" src="' 
                    . $default . '"/></a><span class="rp_headerbox" style="margin-bottom:5px !important;">'
                    . $persona->full_name . '</span><br/>';
            if ( ! $options['hide_dates'] ) {
                $block .= $persona->birth_date
                        . '- ' . $persona->death_date . '<br/>';
            }
            $cnt = count( $persona->notes );
            for ($idx = 0; $idx < $cnt; $idx++) {
                $block .= str_replace( "\n", "<br/>", $persona->notes[$idx]->note );         
            }
        }
        $block .= '</div></div>';
        return $block;
    }

    public static function create_for_edit( $persona, $options ) {
        $tempPic = '';
        $block = '<div class="rp_truncate">'
                . '<div class="rp_header" style="overflow:hidden;">'
                . '<div class="rp_picture" style="text-align:center;width:120px;overflow:hidden;float:left;padding-bottom:10px;">';
        if ( isset( $persona->picFiles[0] ) ) {
            $block .= '<a style="margin-bottom:0px;" href="' . $persona->picFiles[0]
                    . '"><img id="img1" name="img1" src="'
                    . $persona->picFiles[0] . '"';
            $tempPic = $persona->picFiles[0];

        } else {
            $block .= "<a style='margin-bottom:0px;' href='" . $options['plugin_url'] . "/images/";
            if ( isset( $persona->gender )
            && $persona->gender == 'F' ) {
                $block .= "/girl-silhouette.gif'><img id='img1' name='img1' src='"
                    . $options['plugin_url'] . "/images/girl-silhouette.gif'";
            } else {
                $block .= "/boy-silhouette.gif'><img id='img1' name='img1' src='"
                    . $options['plugin_url'] . "/images/boy-silhouette.gif'";
            }
        }

        $block .= ' class="rp_headerbox" style="padding-bottom:0px;margin-bottom:0px ! important;"/>'
            . '</a><input style="display:none;" id="img1_upload" type="text" size="36" name="img1_upload" value="' . $tempPic . '" />'
            . '<input id="img1_upload_button" type="button" value="Change" /></div>'
            . '<div class="rp_headerbox" style="float:left;">' . '<span class="rp_headerbox">'
            . ( isset( $persona->full_name ) ? $persona->full_name : '&#160;' ) . '</span>'
            . '<span class="rp_headerbox" style="padding-left:15px;align:right;color:#7c7c7c">'
            . ( isset( $persona->id ) ? $persona->id : '&#160;' ) . '</span>'
            . '</div></div></div>';

        return $block;
    }
}
?>
