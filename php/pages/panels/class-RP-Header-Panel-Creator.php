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
        $imgprop = '';
        if ( ! isset($persona->picFiles[0])) {
            if( isset( $persona->gender )
            && $persona->gender == 'F' ) {
                $default = $options['plugin_url'] . "/images/girl-silhouette.gif";
            } else {
                $default = $options['plugin_url'] . "/images/boy-silhouette.gif";
            }
        } else {
            $default = $persona->picFiles[0];
            $imgprop = ' itemprop="image" ';
        }
        
        $block = '<section class="rp_truncate">' 
                . '<div class="rp_header" itemscope itemtype="http://historical-data.org/HistoricalPerson.html">'
                . '<meta itemprop="gender" content="' . $persona->gender . '"/>';
        
        $cnt = count( $persona->notes );
        $pframe_color = ( ( isset( $options['pframe_color'] ) && ! empty( $options['pframe_color'] ) ) 
                        ? ("style='border-color:" . $options['pframe_color'] . " !important;'"): '' );
        
        if( ! isset ( $options['header_style'] )  || $options['header_style'] == '1' || $cnt == 0 ) {
            // original style
            $block .= '<div float:left;"><a href="' . $default . '">'
            . '<img class="rp_headerbox" src="' . $default . '" ' . $imgprop . $pframe_color . '/></a></div>'
            . '<div class="rp_headerbox">' 
            . '<span class="rp_headerbox" id="hdr_name" itemprop="name">'
            . $persona->full_name . '</span>'
            . '<span class="rp_headerbox" style="padding-left:15px;align:right;color:#EBDDE2;display:none;">'
            . $persona->id . '</span>';
            
            if ( ! $options['hide_dates'] ) {
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->birth_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">' 
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'' )                       
                         . @preg_replace( '/@.*@(.*)/US', '$1', $persona->birth_date ) 
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';
                
                $block .= '<br/>b: ' . $tmpDate;
                
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->death_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">' 
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'')                        
                         . @preg_replace( '/@.*@(.*)/US', '$1', $persona->death_date ) 
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';
                
                $block .= '<br/>d: ' . $tmpDate;
            }
            
            $block .= '</div>';
        } else if ( $options['header_style'] == '2' ) {
            // bio style
            $block .= '<a href="' . $default . '">'
                    . '<img class="rp_headerbox rp_biopic" '. $pframe_color 
                    . ' src="'
                    . $default . '"/></a><span class="rp_headerbox"  itemprop="name" style="margin-bottom:5px !important;">'
                    . $persona->full_name . '</span><br/>';
            if ( ! $options['hide_dates'] ) {
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->birth_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="birth" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">' 
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'' )                       
                         . @preg_replace( '/@.*@(.*)/US', '$1', $persona->birth_date ) 
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';
                
                $block .= '<br/>b: ' . $tmpDate;
                
                $d = @preg_replace( '/@.*@(.*)/US', '$1', $persona->death_date );
                $year = preg_replace ("/.*([0-9][0-9][0-9][0-9]).*/i", '$1', $d);
                $tmpDate = '<span itemprop="death" itemscope itemtype="http://historical-data.org/HistoricalEvent.html">' 
                         . (strlen($year)==4?'<span itemprop="startDate" content="' . $year . '">':'')                        
                         . @preg_replace( '/@.*@(.*)/US', '$1', $persona->death_date ) 
                         . (strlen($year)==4?'</span>':'')
                         . '</span>';
                
                $block .= '<br/>d: ' . $tmpDate;
            }
            $cnt = count( $persona->notes );
            for ($idx = 0; $idx < $cnt; $idx++) {
                $block .= str_replace( "\n", "<br/>", $persona->notes[$idx]->note );
            }
        }
        $block .= '</div></section>';
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
