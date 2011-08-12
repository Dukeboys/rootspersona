<?php

class RP_Tools_Page_Builder {

    /**
     *
     * @param array $options
     */
    function build( $options ) {
        $win1 = __( 'All persona pages will be deleted. Does not include utilities. Proceed?',
                'rootspersona' );
        $win2 = __( 'All persona files will be used to populate the database tables','rootspersona')
            . '. ' . __('The files will NOT be deleted. Proceed?', 'rootspersona' );

        $block = "<div class='wrap'><h2>rootsPersona</h2>"
                . "<table class='form-table'>"

                // Upload
                . "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'><div class='rp_linkbutton'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=upload') . "'>"
                
                . __( 'Upload GEDCOM', 'rootspersona' ) . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . __( 'Upload (or re-upload) a GEDCOM file.', 'rootspersona' )
                . "</td></tr>"

                //  Add
                . "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'><div class='rp_linkbutton'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=create') . "'>"
                
                . __( 'Add Uploaded Persons', 'rootspersona' )
                . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . __( 'Review the list of people you have uploaded but not created pages for.', 'rootspersona' )
                . "</td></tr>"

                // Excluded
                . "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'><div class='rp_linkbutton'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=include') . "'>"
                
                . __( 'Review Excluded Persons', 'rootspersona' ) . "</a></div></td>" . "<td style='vertical-align:middle'>"
                . __( 'Review people you have previous excluded, and include the ones you select.', 'rootspersona' ) . "</td></tr>"

                // Validate
                . "<tr style='vertical-align: top'>" . "<td style='width:200px;'><div class='rp_linkbutton'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=util')
                . "&utilityAction=validatePages'>"
                . __( 'Validate persona Pages', 'rootspersona' ) . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . sprintf( __( 'Identify orphaned %s pages. Includes all pages with %s shortcode and no reference in the database, or reference in the database with no corresponding page.', 'rootspersona' ), "persona", "[rootsPersona/]" )
                . "<br/>"
                . __( "Will also identify/sync pages with the wrong parent page assigned." )
                . "</td></tr>"

                // Add Evidence
                . "<tr style='vertical-align: top'>"
                . "<td style='width:200px;'><div class='rp_linkbutton'><a href=' "
                . admin_url('/tools.php?page=rootsPersona&rootspage=util')
                . "&utilityAction=addEvidencePages'>"
                . __( 'Add Evidence Pages', 'rootspersona' ) . "</a></div></td>" . "<td style='vertical-align:middle'>"
                . __( 'Add missing evidence pages.', 'rootspersona' ) . "</td></tr>"

                // Delete persona
                . "<tr style='vertical-align: top'>" . "<td style='width:200px;'><div class='rp_linkbutton'>"
                . "<a href='#' onClick='javascript:rootsConfirm(\"" . $win1 . "\",\""
                . admin_url('/tools.php?page=rootsPersona&rootspage=util')
                . "&utilityAction=delete\");return false;'>"
                . __( 'Delete persona Pages', 'rootspersona' )
                . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . sprintf( __( 'Perform a bulk deletion of all %s and evidence pages. This will NOT delete data, only pages.', 'rootspersona' ), "persona", "[rootsPersona/]" )
                . "</td></tr>"

                // Convert
                . "<tr style='vertical-align: top'>" . "<td style='width:200px;'><div class='rp_linkbutton'>"
                . "<a href='#' onClick='javascript:rootsConfirm(\"" . $win2 . "\",\""
                . admin_url('/tools.php?page=rootsPersona&rootspage=util')
                . "&utilityAction=convert2\");return false;'>"
                . __( 'Convert to 2.x Format', 'rootspersona' )
                . "</a></div></td>"
                . "<td style='vertical-align:middle'>"
                . __( 'Perform a bulk conversion from the pre 2.x file format to the 2.x database format.', 'rootspersona' )
                . "</td></tr>"
                . "</table>"
                . "</div>";
        return $block;
    }
}
?>
