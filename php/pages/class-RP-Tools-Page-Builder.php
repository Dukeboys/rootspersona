<?php

class RP_Tools_Page_Builder {

     CONST hover = "onmouseover='jQuery(this).removeClass().addClass(\"rp_hoverbutton\");' onmouseout='jQuery(this).removeClass().addClass(\"rp_linkbutton\");' onmousedown='jQuery(this).removeClass().addClass(\"rp_clickbutton\");' onmouseup='jQuery(this).removeClass().addClass(\"rp_linkbutton\");'";
    /**
     *
     * @param array $options
     */
    function build( $options, $batch_ids ) {
        $block = "<div class='wrap'>"
                . "<span class='rp_hoverbutton' style='background-position: -1000px -1000px;'></span>"
                . "<span class='rp_clickbutton' style='background-position: -1000px -1000px;'></span>"
                . "<h2>rootspersona</h2>"
                . "<table class='form-table'>";

        $block .=  $this->get_upload();
        $block .=  $this->get_add();
        $block .=  $this->get_evidence();
        $block .=  $this->get_excluded();
        $block .=  $this->get_validate();
        $block .=  $this->get_delete();
        //$block .=  $this->get_conversion();

        $block .= "</table>" . $this->get_secondary($batch_ids) . "</div>";
        return $block;
    }

    function get_upload() {
        $block =  "<tr style='vertical-align: top'>"
            . "<td style='width:200px;'><div class='rp_linkbutton' " . RP_Tools_Page_Builder::hover 
            . " onclick='window.open(\"" . admin_url('/tools.php?page=rootsPersona&rootspage=upload') 
                . "\");'><a href=' "
            . admin_url('/tools.php?page=rootsPersona&rootspage=upload') . "'>"

            . __( 'Upload GEDCOM', 'rootspersona' ) . "</a></div></td>"
            . "<td style='vertical-align:middle'>"
            . __( 'Upload (or re-upload) a GEDCOM file.', 'rootspersona' )
            . "</td></tr>";
        return $block;
    }

    function get_add() {
        $block =  "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'><div class='rp_linkbutton' " . RP_Tools_Page_Builder::hover 
                . " onclick='window.open(\"" . admin_url('/tools.php?page=rootsPersona&rootspage=create') 
                . "\");'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=create') . "'>"

                . __( 'Add Uploaded Persons', 'rootspersona' )
                . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . __( 'Review the list of people you have uploaded but not created pages for.', 'rootspersona' )
                . "</td></tr>";
        return $block;
    }

    function get_excluded() {
        $block =  "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'>"
                . "<div class='rp_linkbutton'" .  RP_Tools_Page_Builder::hover 
                . " id='review' name='review' onclick='revealBatchSpan(this,\"" . admin_url() . "\");'>"
                . __( 'Review Excluded Persons', 'rootspersona' ) . "</div></td>"
                . "<td style='vertical-align:middle'>";

        $block .=  "<span id='batchlabel'>"
                . __( 'Review people you have previous excluded, and include the ones you select.', 'rootspersona' )
               . "</span></td></tr>";
        return $block;
    }

    function get_validate() {
        $block =  "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'>"
                . "<div class='rp_linkbutton'" .  RP_Tools_Page_Builder::hover 
                . " id='validate' name='validate' onclick='revealBatchSpan(this,\"" . admin_url() . "\");'>"
                . __( 'Validate persona Pages', 'rootspersona' ) . "</div></td>"
                . "<td style='vertical-align:middle'>";

        $block .=  "<span id='batchlabel'>"
                . sprintf( __( 'Identify orphaned %s pages. Includes all pages with %s shortcode and no reference in the database, or reference in the database with no corresponding page.', 'rootspersona' ), "persona", "[rootsPersona/]" )
                . __( "Will also identify/sync pages with the wrong parent page assigned." )
               . "</span></td></tr>";
        return $block;
    }

    function get_delete() {
        $block =  "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'>"
                . "<div class='rp_linkbutton'" .  RP_Tools_Page_Builder::hover 
                . " id='delete' name='delete' onclick='revealBatchSpan(this,\"" . admin_url() . "\");'>"
                . __( 'Delete persona Pages', 'rootspersona' ) . "</div></td>"
                . "<td style='vertical-align:middle'>";

        $block .=  "<span id='batchlabel'>"
                . sprintf( __( 'Perform a bulk deletion of all %s and evidence pages. You will be promoted before the data is deleted.', 'rootspersona' ), 'rootspersona')
               . "</span></td></tr>";
        return $block;
    }

    function get_evidence( ) {
        $action = 'addEvidencePages';
        $block =  "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'>"
                . "<div class='rp_linkbutton'" .  RP_Tools_Page_Builder::hover
                . " id='evidence' name='evidence' onclick='revealBatchSpan(this,\"" . admin_url() . "\");'>"
                . __( 'Add Evidence Pages', 'rootspersona' ) . "</div></td>"
                . "<td style='vertical-align:middle'>";

      $block .=  "<span id='batchlabel'>" . __( 'Add missing evidence pages.', 'rootspersona' )
               . "</span></td></tr>";
      return $block;
    }

    function get_secondary($batch_ids) {
        $default = count( $batch_ids ) > 0 ? $batch_ids[0] : '1';

        $s = count( $batch_ids ) <= 1 ? '2' : '4';

        $block =  '';


      $block .=  "<div id='batchspan' style='background-color:#DDDDDD;width:35em;padding:5px;display:none;overflow:hidden;border:solid blue 2px;'>"
                . "<label class='label8' style='font-weight:bold;' for='batch_id'>Select Batch Id:</label>"
                . "<input type='text' name='batch_id' id='batch_id' size='6' value='$default' />"
                . "<input type='button' hidefocus='1' value='&#9660;'"
                . "style='height:13;width:13;font-family:helvetica;padding:2px;' "
                . "onclick='if( jQuery(\"#batch_ids_span\").is(\":visible\") ) jQuery(\"#batch_ids_span\").css(\"display\",\"none\"); else jQuery(\"#batch_ids_span\").css(\"display\",\"inline-block\");'>"

                . "<input style='margin-left:0.5em;' id='process_button' name ='process_button' type='button' value='Process'>"
                . "<input type='button' value='Cancel' onclick=\"jQuery('#batchspan').hide();jQuery('#batchlabel').show();return false;\">"

                . "<span style='display:inline-block;width:12em;font-weight:bold;font-size:smaller;font-style:italic;color:red;margin-left:10px;'>"
                . "Only change if you have segregated data.</span>"
                . "<br/><span id='batch_ids_span' name='batch_ids_span' style='display:none;overflow:hidden;margin:-4px 0px 0px 0px;'>"
                . "<label class='label8' for='batch_ids'>&nbsp;</label>"
                . "<select id='batch_ids' name='batch_ids' style='width:7.6em;z-index=4;'"
                . " onchange='synchBatchText();' size='$s'>";

      foreach ( $batch_ids as $id ) {
           $selected = $id==$default?'selected':'';
           $block .= "<option value='$id' $selected>$id&nbsp;&nbsp;</option>";
      }

      $block .= "</select></span>"
                . "</div>";

      return $block;
    }
}
?>
