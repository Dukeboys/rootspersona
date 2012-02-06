<?php

class RP_Evidence_Panel_Creator {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    public static function create( $persona, $options ) {
        $block = '<section class="rp_truncate">';
        $block .= RP_Persona_Helper::get_banner($options, __( 'Evidence', 'rootspersona' ) );
        $block .= '<div class="rp_evidence">';
        $block .= '<table class="rp_evi">';
        $cnt = count( $persona->sources );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            if( isset( $persona->sources[$idx]['page_id'] ) 
                    && ! empty( $persona->sources[$idx]['page_id'] ) ) {
                $link = '<a href="' . $options['home_url'] . "?page_id=" 
                    . $persona->sources[$idx]['page_id'] . '">'
                    . '[' . $persona->sources[$idx]['src_id'] . ']</a>';
            } else {
               $win1 = sprintf ( __( 'Evidence page has not yet been created for this source',
                'rootspersona' ), 'persona' )
               . ".";
                $link = "<a href='#' onClick='javascript:rootsConfirm(\"" . $win1 . "\",\"\");return false;'>"
                    . '[' . $persona->sources[$idx]['src_id'] . ']</a>';                
            }
                
            $block .= '<tr><td valign="top"><sup>'
                . $link
                . '</sup></td><td><span style="padding-left:10px;display:inline-block;">' 
                . $persona->sources[$idx]['src_title']
                . '</span></td></tr>';
        }
        $block .= '</table></div></section>';
        return $block;
    }
}
?>
