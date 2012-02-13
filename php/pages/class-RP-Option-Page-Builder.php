<?php

class RP_Option_Page_Builder {

    /**
     *
     * @param array $options
     */
    function build( $options ) {
       $win1 = sprintf ( __( 'Edit page is meant to be accessed using the Edit button on a %s page',
                'rootspersona' ), 'persona' )
               . ". " . sprintf ( __( 'Accessing outside of a %s page will result in an invalid personId error.',
                'rootspersona' ), 'persona' );
        echo "<div class='wrap'><h2>rootspersona <span style='font-size:smaller;'>" . $options['version'] . "</span></h2>";
        echo "<form method='post' action='options.php'>";
        echo "<p class='submit'><input type='submit' name='Submit' value=' " . __( 'Save Changes', 'rootspersona' ) . " '/></p>";
        echo "<table class='form-table'>";
        echo "<tr><td colspan='3'><span class='optionsHdr'>" . __('Privacy Options','rootspersona') . "</span><hr class='optionsHdr'></span></td></tr>";
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[privacy_living]'>" . __( 'Living Privacy Setting', 'rootspersona' ) . "</label></td>";
        $opt = $options['privacy_living'];
        $exc = '';
        $pvt = "selected";
        $mbr = '';
        $pub = '';
        if ( isset( $opt ) ) {
            if ( $opt == 'Pub' ) {
                $pub = "selected";
                $pvt = '';
            } else if ( $opt == 'Mbr' ) {
                $mbr = "selected";
                $pvt = '';
            } else if ( $opt == 'Exc' ) {
                $exc = "selected";
                $pvt = '';
            }
        }
        echo "<td><select name='persona_plugin[privacy_living]'><option value='Exc' $exc>Exclude FROM site</option>";
        echo "<option value='Pvt' $pvt>" . __('Private, Admins Only','rootspersona') . "&#160;</option>";
        echo "<option value='Mbr' $mbr>" . __('Members Only','rootspersona') . "</option>";
        echo "<option value='Pub' $pub>" . __('Public','rootspersona') . "</option></select></td>";
        echo "<td>" . __( 'Privacy Setting to use for living people (no death date and birth date greater than 110 years ago)', 'rootspersona' ) . ".</td></tr>";
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[privacy_default]'>" . __( 'Default Privacy Setting', 'rootspersona' ) . "</label></td>";
        $opt = $options['privacy_default'];
        $pub = "selected";
        $mbr = '';
        $pvt = '';
        if ( isset( $opt ) ) {
            if ( $opt == 'Pvt' ) {
                $pvt = "selected";
                $pub = '';
            } else if ( $opt == 'Mbr' ) {
                $mbr = "selected";
                $pub = '';
            }
        }
        echo "<td><select name='persona_plugin[privacy_default]'><option value='Pvt' $pvt>" . __('Private, Admins Only','rootspersona') . "&#160;</option>";
        echo "<option value='Mbr' $mbr>" . __('Members Only','rootspersona') . "</option>";
        echo "<option value='Pub' $pub>" . __('Public','rootspersona') . "</option></select></td>";
        echo "<td>" . __( 'Privacy Setting to use when nothing else applies', 'rootspersona' ) . ".</td></tr>";
        echo "<tr valign='top' class='left-label'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[hide_dates]'>" . __( 'Hide Dates', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_dates'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_dates]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_dates]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . sprintf( __( 'Some people may want to hide dates for privacy purposes.  This is a global flag (impacts all %s pages).', 'rootspersona' ), "persona" ) . "</td></tr>";
        echo "<tr valign='top' class='left-label'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[hide_places]'>" . __( 'Hide Locations', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_places'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_places]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_places]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . sprintf( __( 'Some people may want to hide locations for privacy purposes.  This is a global flag (impacts all %s pages).', 'rootspersona' ), "persona" ) . "</td></tr>";
        echo "<tr><td colspan='3'><span class='optionsHdr'>" . __('Display Options','rootspersona') . "</span><hr class='optionsHdr'></span></td></tr>";

        // Header Style
        echo "<tr valign='top' class='left-label'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[header_style]'>" . __( 'Header Style', 'rootspersona' ) . "?</label></td>";
        $opt1 = $options['header_style'];
        if ( isset( $opt1 )
        && $opt1 == '1' ) {
            $opt1 = 'checked';
            $opt2 = '';
        } else {
            $opt1 = '';
            $opt2 = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[header_style]' value='1' $opt1>" . __('Original','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[header_style]' value='2' $opt2>" . __('Bio','rootspersona') . "</td>";
        echo "<td>" . __( 'Choose a style for the header panel', 'rootspersona' ) . ".</td></tr>";

        // Header
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_header]'>" . __( 'Hide Header', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_header'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_header]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_header]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Header Panel on persona pages.', 'rootspersona' ) . "</td></tr>";

        // Bio
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_bio]'>" . __( 'Hide Bio', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_bio'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_bio]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_bio]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Biography panel on persona pages', 'rootspersona' ) . ".</td></tr>";

        // Facts
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_facts]'>" . __( 'Hide Facts', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_facts'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_facts]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_facts]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Facts panel on persona pages', 'rootspersona' ) . ".</td></tr>";

        // Ancestors
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_ancestors]'>" . __( 'Hide Ancestors', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_ancestors'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_ancestors]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_ancestors]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Ancestors panel on persona pages', 'rootspersona' ) . ".</td></tr>";

        // Descendancy
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_descendancy]'>" . __( 'Hide Descendancy', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_descendancy'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_descendancy]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_descendancy]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Descendancy panel on persona pages', 'rootspersona' ) . ".</td></tr>";

        // Family - Child
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_family_c]'>" . __( 'Hide Child Family Group', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_family_c'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_family_c]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_family_c]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Family Group panel where the person is a Child on persona pages', 'rootspersona' ) . ".</td></tr>";

        // Family - Spouse
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_family_s]'>" . __( 'Hide Spousal Family Groups', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_family_s'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_family_s]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_family_s]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the Family Group panels where the person is a Spouse on persona pages', 'rootspersona' ) . ".</td></tr>";
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_pictures]'>" . __( 'Hide Picture', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_pictures'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_pictures]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_pictures]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . sprintf( __( 'Skip the pictures panel on %s pages. Still displays the image in the Header panel', 'rootspersona' ), "persona" ) . ".</td></tr>";
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_evidence]'>" . __( 'Hide Evidence', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_evidence'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_evidence]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_evidence]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . __( 'Skip the evidence panel in persona pages.', 'rootspersona' ) . "</td></tr>";
      
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_edit_links]'>" . __( 'Hide Edit Links', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_edit_links'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_edit_links]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_edit_links]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" . sprintf( __( 'Some people may want to hide the edit links at the bottom of the %s page', 'rootspersona' ), "persona" ) . ".</td></tr>";
       
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[hide_undef_pics]'>" . __( 'Hide Silhouettes', 'rootspersona' ) . "?</label></td>";
        $yes = $options['hide_undef_pics'];
        if ( isset( $yes )
        && $yes == '1' ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[hide_undef_pics]' value='1' $yes>" . __('Yes','rootspersona');
        echo "&#160;<input type='radio' name='persona_plugin[hide_undef_pics]' value='0' $no>" . __('No','rootspersona') . "</td>";
        echo "<td>" .__( 'Some people may want to hide the silhouette placeholders in the picture panel', 'rootspersona' ) . ".</td></tr>";
       
        // Misc
        echo "<tr><td colspan='3'><span class='optionsHdr'>" . __('Utility Pages','rootspersona') . "</span><hr class='optionsHdr'></span></td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[parent_page]'>" . __( 'Parent Page Id', 'rootspersona' ) . "</label></td>";
        echo "<td><input type='text' size='5' name='persona_plugin[parent_page]' id='parent_page'";
        echo " value='" . $options['parent_page'] . "'/></td>";
        echo "<td><a href=' " . $options['home_url'] . "?page_id=" . $options['parent_page'] . "'>" . __( 'Page', 'rootspersona' ) . "</a> " 
                . sprintf( __( 'you want %s pages to be organized under in a menu structure.  0 indicates no parent page', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr><td colspan='3'><span class='optionsHdr'>" . __('Style Options','rootspersona') . "</span><hr class='optionsHdr'></span></td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[banner_fcolor]'>" . __( 'Banner Foreground Color', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'>color:<input type='text' size='8' name='persona_plugin[banner_fcolor]' id='banner_fcolor'";
        echo " value='" . ( isset( $options['banner_fcolor'] ) ?  $options['banner_fcolor'] : '' ) . "'/>;</td>";
        //echo "<td>" . sprintf( __( 'Overrides the default foreground color for the banners between %s panels.', 'rootspersona' ), "persona" ) . "</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[banner_bcolor]'>" . __( 'Banner Background Color', 'rootspersona' ) . "</label></td>";
        echo "<td colsspan='2'>background-color:<input type='text' size='8' name='persona_plugin[banner_bcolor]' id='banner_bcolor'";
        echo " value='" . ( isset( $options['banner_bcolor'] ) ?  $options['banner_bcolor'] : '' ) . "'/>;</td>";
       // echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[banner_image]'>" . __( 'Banner Image', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'>background-image:url('<input type='text' size='65' name='persona_plugin[banner_image]' id='banner_image'";
        echo " value='" . ( isset( $options['banner_image'] ) ?  $options['banner_image'] : '' ) . "'/>');</td>";
        //echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";


        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[group_fcolor]'>" . __( 'Group Foreground Color', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'>color:<input type='text' size='8' name='persona_plugin[group_fcolor]' id='group_fcolor'";
        echo " value='" . ( isset( $options['group_fcolor'] ) ?  $options['group_fcolor'] : '' ) . "'/>;</td>";
        //echo "<td>" . sprintf( __( 'Overrides the default foreground color for the banners between %s panels.', 'rootspersona' ), "persona" ) . "</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[group_bcolor]'>" . __( 'Group Background Color', 'rootspersona' ) . "</label></td>";
        echo "<td colsspan='2'>background-color:<input type='text' size='8' name='persona_plugin[group_bcolor]' id='group_bcolor'";
        echo " value='" . ( isset( $options['group_bcolor'] ) ?  $options['group_bcolor'] : '' ) . "'/>;</td>";
       // echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[group_image]'>" . __( 'Group Image', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'>background-image:url('<input type='text' size='65' name='persona_plugin[group_image]' id='group_image'";
        echo " value='" . ( isset( $options['group_image'] ) ?  $options['group_image'] : '' ) . "'/>');</td>";
        //echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[pframe_color]'>" . __( 'Persona Frames', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'>border-color:<input type='text' size='8' name='persona_plugin[pframe_color]' id='pframe_color'";
        echo " value='" . ( isset( $options['pframe_color'] ) ?  $options['pframe_color'] : '' ) . "'/>;</td>";
        //echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[index_even_color]'>" . __( 'Index Even Row Color', 'rootspersona' ) . "</label></td>";
        echo "<td colsspan='2'>background-color:<input type='text' size='8' name='persona_plugin[index_even_color]' id='index_even_color'";
        echo " value='" . ( isset( $options['index_even_color'] ) ?  $options['index_even_color'] : '' ) . "'/>;</td>";
       // echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[index_odd_color]'>" . __( 'Index Odd Row Color', 'rootspersona' ) . "</label></td>";
        echo "<td colsspan='2'>background-color:<input type='text' size='8' name='persona_plugin[index_odd_color]' id='index_odd_color'";
        echo " value='" . ( isset( $options['index_odd_color'] ) ?  $options['index_odd_color'] : '' ) . "'/>;</td>";
       // echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[index_hdr_color]'>" . __( 'Index Header Row Color', 'rootspersona' ) . "</label></td>";
        echo "<td colsspan='2'>background-color:<input type='text' size='8' name='persona_plugin[index_hdr_color]' id='index_hdr_color'";
        echo " value='" . ( isset( $options['index_hdr_color'] ) ?  $options['index_hdr_color'] : '' ) . "'/>;</td>";
       // echo "<td>" . sprintf( __( 'Overrides the default background image for the banners between %s panels. If both color and image are provided, color will take precedence', 'rootspersona' ), "persona" ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left-label'><label for='persona_plugin[custom_style]'>" . __( 'Custom Style', 'rootspersona' ) . "</label></td>";
        echo "<td colspan='2'><label style='vertical-align:top;'>&lt;style&gt;</label><textarea cols='65' rows='4' name='persona_plugin[custom_style]' id='custom_style' />";
        echo ( isset( $options['custom_style'] ) ?  $options['custom_style'] : '' ) . "</textarea>&lt;/style&gt;</span></td>";
        
        echo "<tr><td colspan='3'><span class='optionsHdr'>" . __('Misc Options','rootspersona') . "</span><hr class='optionsHdr'></span></td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[per_page]'>" . __( 'Entries Per Page', 'rootspersona' ) . "</label></td>";
        echo "<td><input type='text' size='5' name='persona_plugin[per_page]' id='per_page'";
        echo " value='" . ( isset( $options['per_page'] ) ?  $options['per_page'] : '' ) . "'/></td>";
        echo "<td>" . __( 'Indicates the number of rows on an index page. Default is 25.', 'rootspersona' ) . ".</td></tr>";

        echo "<tr valign='top'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[is_system_of_record]'>" . __( 'System Of Record', 'rootspersona' ) . "?</label></td>";
        $yes = $options['is_system_of_record'];
        if ( isset( $yes )
        && $yes == 1 ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='radio' name='persona_plugin[is_system_of_record]' value='true' $yes disabled>Yes ";
        echo "&#160;<input type='radio' name='persona_plugin[is_system_of_record]' value='false' $no>No </td>";
        echo "<td>" . __( 'Only No is supported at this time (meaning some external program is the system of record)', 'rootspersona' ) . ".</td></tr>";
             
        echo "<tr valign='top'>";
        echo "<td scope='row' class='left=label' ><label for='persona_plugin[debug]'>" . __( 'Debug Mode', 'rootspersona' ) . "?</label></td>";
        $yes = $options['debug'];
        if ( isset( $yes )
        && $yes == 1 ) {
            $yes = 'checked';
            $no = '';
        } else {
            $yes = '';
            $no = 'checked';
        }
        echo "<td><input type='checkbox' name='persona_plugin[debug]' value='true' $yes>&#160;&#160;Yes ";

        echo "<td>" . __( 'Provide debug information in wp-content/debug.log', 'rootspersona' ) . ".</td></tr>";
       
        echo "</table><p class='submit'>";
        echo "<input type='submit' name='Submit' value=' " . __( 'Save Changes', 'rootspersona' ) . " '/>";
        echo settings_fields( 'persona_plugin' );
        echo "</p></form></div>";
    }
}
?>
