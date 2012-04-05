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
        $isSOR = ($options['is_system_of_record'] == '1'?true:false);
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
            . '<input id="img1_upload_button" type="button" value="Browse" /></div>'
            . '<div class="rp_headerbox" style="float:left;padding:5px;">'
            . '<span class="rp_headerbox" style="color:#7c7c7c;display:block;margin-bottom:10px;">Id: '
            . ( isset( $persona->id ) ? $persona->id : '&#160;' )
            . '&#160;&#160;Batch Id: '
            . ( isset( $persona->batch_id ) ? $persona->batch_id : '&#160;' )
            . '</span>';

       if($isSOR) {
           if(!empty($persona->surname)) {
            $block .= "<label class='label6' for='_prefix'>Title"
                . ":</label>"
                . "<input id='_prefix' name='_prefix' type='text' size='6' value='"
                . $persona->prefix . "' >"
                . "<span style='font-size:smaller;font_style:italic;margin-left:10px;'>(Rev., Col., Dr., etc...)</span>"
                . "<br/>"
                . "<label class='label6' for='_last' >SurName:</label>"
                . "<input id='_last' name='_last' type='text' size='28' value='"
                . $persona->surname . "' >"
                . "<label class='label4' id='section_label' for='_first'>Given:</label>"
                . "<input id='_first' name='_first' type='text' size='28' value='"
                . $persona->given . "' >"
                . "<label class='label4' for='_middle'>Nick:</label>"
                . "<input id='_middle' name='_middle' type='text' size='6' value='"
                . $persona->nickname . "' >"
                . "<br/>"
                . "<label class='label6' for='_suffix'>Suffix"
                . ":</label>"
                . "<input id='_suffix' name='_suffix' type='text' size='6' value='"
                . $persona->suffix . "' >"
                . "<span style='margin-right:4px;font-size:smaller;font_style:italic;margin-left:10px;'>(Sr., Jr., etc...)</span>";
           } else {
                $block .= "<label class='label6' for='_last' >Full Name:</label>"
                . "<input id='_full' name='_full' type='text' size='40' value='"
                . ( isset( $persona->full_name ) ? $persona->full_name : '' ) . "' >";
           }
       } else {
            $block .= '<span class="rp_headerbox" style="margin-top:15px;">'
                    . ( isset( $persona->full_name ) ? $persona->full_name : '&#160;' )
                    . '</span>';
      }

       if($isSOR) {
            $m = '';
            $f = '';
            $u = 'checked';
            if( isset( $persona->gender ) ) {
               if ($persona->gender == 'F' ) {
                    $f = 'checked';
                    $m = '';
                    $u = '';
                } else if ($persona->gender == 'M' ) {
                    $f = '';
                    $m = 'checked';
                    $u = '';
                }
           }
            $block .= '<br/><span style="margin:10px 5px 5px 5px;display:block">'
                    . '<input type="radio" name="pickgender" id="male" value="M" ' . $m . '/>Male'
                    . '&#160;&#160;<input type="radio" name="pickgender" id="female" value="F" ' . $f . '/>Female'
                    . '&#160;&#160;<input type="radio" name="pickgender" id="unknown" value="U" ' . $u . '/>Unknown'
                    . '</span>';
       }

       $block .= '</div></div></div>';

        return $block;
    }
}
?>
