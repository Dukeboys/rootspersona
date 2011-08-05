<?php

class RP_Evidence_Panel_Creator {

    /**
     *
     * @param RP_Persona $persona
     * @param array $options
     * @return string
     */
    public static function create( $persona, $options ) {
        $block = '<div class="rp_truncate">';
        $block .= '<div class="rp_evidence">';
        $block .= '<table class="rp_evi">';
        $cnt = count( $persona->sources );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $block .= '<tr><td valign="top"><sup>'
                . '<a href="' . $options['home_url'] . "?page_id=" 
                . $persona->sources[$idx]['page_id'] . '">'
                . '[' . $persona->sources[$idx]['src_id'] . ']</a>'
                . '</sup></td><td><span style="padding-left:10px;display:inline-block;">' 
                . $persona->sources[$idx]['src_title']
                . '</span></td></tr>';
        }
        $block .= '</table></div></div>';
        return $block;
    }
}
?>
