<?php
require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/class-RP-Table-Creator.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/class-RP-Xml-To-Database-Importer.php' );
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Credentials.php' );

class RP_Persona_Installer {
    var $sql_file_to_create_tables;
    var $sql_file_to_drop_tables;

    /**
     *
     */
    function __construct() {
        $this->sql_file_to_create_tables = WP_PLUGIN_DIR . '/rootspersona/sql/create_tables.sql';
        $this->sql_file_to_drop_tables = WP_PLUGIN_DIR . '/rootspersona/sql/drop_tables.sql';
    }

    /**
     *
     * @param string $plugin_dir
     * @param string $version
     * @param array $options
     * @param string $prefix
     */
    function persona_install( $plugin_dir, $version, $options, $prefix ) {
        if( isset( $options['version'] ) ) {
             $this->persona_upgrade( $plugin_dir, $version, $options, $prefix );
        } else {
            $creator = new RP_Table_Creator();
            $creator->update_tables( $this->sql_file_to_create_tables, $prefix );

            $options = array();
            $options['version'] = $version;

            $page = $this->create_page( __( 'Person Index', 'rootspersona' ),
                    '[rootsPersonaIndexPage batchId="1"/]', '', 'publish'  );
            $options['index_page'] = $page;
            $page = $this->create_page( __( 'Evidence Index', 'rootspersona' ),
                    '[rootsEvidencePage/]', '', 'publish' );
            $options['evidence_page'] = $page;

            $parent_page = $this->get_parent_page();
            $page = $this->create_page( __( 'rootspersona Tree', 'rootspersona' ),
                    $parent_page, '', 'publish' );
            $options['parent_page'] = $page;

            $options['is_system_of_record'] = 0;
            $options['privacy_default'] = 'Pub';
            $options['privacy_living'] = 'Mbr';
            $options['header_style'] = 1;
            $options['hide_header'] = 0;
            $options['hide_facts'] = 0;
            $options['hide_bio'] = 0;
            $options['hide_ancestors'] = 0;
            $options['hide_family_c'] = 0;
            $options['hide_family_s'] = 0;
            $options['hide_evidence'] = 0;
            $options['hide_pictures'] = 0;
            $options['hide_edit_links'] = 0;
            $options['hide_dates'] = 0;
            $options['hide_places'] = 0;
            $options['per_page'] = 25;
            update_option( 'persona_plugin', $options );
        }
    }

    /**
     *
     * @param string $plugin_dir
     * @param string $version
     * @param array $options
     * @param string $prefix
     */
    function persona_upgrade( $plugin_dir, $version, $options, $prefix ) {

        if ( $options[ 'version' ] < '1.4.0' ) {
            delete_option( 'rootsHideFamily' );
            unregister_setting( 'persona_plugin', 'rootsHideFamily' );
        }

        if ( $options[ 'version' ] < '2.0.0' ) {
            $options['parent_page'] = get_option( 'rootsPersonaParentPage', null );
            $opt = get_option( 'rootsIsSystemOfRecord', 0 );
            $options['is_system_of_record'] = ( ( $opt == false ) ? 0 : 1 );
            $options['index_page'] = get_option( 'rootsPersonaIndexPage' );
            $options['evidence_page'] = get_option( 'rootsEvidencePage' );
            $options['hide_header'] = get_option( 'rootsHideHeader', 0 );
            $options['hide_bio'] = 0;
            $options['header_style'] = 1;
            $options['hide_facts'] = get_option( 'rootsHideFacts', 0 );
            $options['hide_ancestors'] = get_option( 'rootsHideAncestors', 0 );
            $options['hide_family_c'] = get_option( 'rootsHideFamilyC', 0 );
            $options['hide_family_s'] = get_option( 'rootsHideFamilyS', 0 );
            $options['hide_evidence'] = get_option( 'rootsHideEvidence', 0 );
            $options['hide_pictures'] = get_option( 'rootsHidePictures', 0 );
            $options['hide_edit_links'] = get_option( 'rootsHideEditLinks', 0 );
            $options['hide_dates'] = get_option( 'rootsPersonaHideDates', 0 );
            $options['hide_places'] = get_option( 'rootsPersonaHidePlaces', 0 );
            $options['privacy_default'] = 'Pub';
            $options['privacy_living'] = 'Mbr';
            $options['per_page'] = 25;

            $page = get_option( 'rootsUtilityPage' );
            wp_delete_post( $page );
            $page = get_option( 'rootsEditPage' );
            wp_delete_post( $page );            
            $page = get_option( 'rootsIncludePage' );
            wp_delete_post( $page );
            $page = get_option( 'rootsCreatePage' );
            wp_delete_post( $page );
            $page = get_option( 'rootsUploadGedcomPage' );
            wp_delete_post( $page );

            delete_option( 'rootsDataDir' );
            delete_option( 'rootsPersonaParentPage' );
            delete_option( 'rootsIsSystemOfRecord' );
            delete_option( 'rootsUploadGedcomPage' );
            delete_option( 'rootsCreatePage' );
            delete_option( 'rootsEditPage' );
            delete_option( 'rootsIncludePage' );
            delete_option( 'rootsPersonaIndexPage' );
            delete_option( 'rootsUtilityPage' );
            delete_option( 'rootsEvidencePage' );
            delete_option( 'rootsHideHeader' );
            delete_option( 'rootsHideFacts' );
            delete_option( 'rootsHideAncestors' );
            delete_option( 'rootsHideFamilyC' );
            delete_option( 'rootsHideFamilyS' );
            delete_option( 'rootsHideEvidence' );
            delete_option( 'rootsHidePictures' );
            delete_option( 'rootsHideEditLinks' );
            delete_option( 'rootsPersonaHideDates' );
            delete_option( 'rootsPersonaHidePlaces' );
            delete_option( 'rootsPrivacyDefault' );
            delete_option( 'rootsPrivacyLiving' );

            $creator = new RP_Table_Creator();
            $creator->update_tables( $this->sql_file_to_create_tables, $prefix );
        }

        $options['version'] = $version;

        if ( ! isset( $options['evidence_page'] )
        || empty( $options['evidence_page'] ) ) {
            $page = $this->create_page( __( 'Evidence Index', 'rootspersona' ),
                    "[rootsEvidencePage batchId='1'/]", '', 'publish' );
            $options['evidence_page'] = $page;
        } else {
            $this->create_page( __( 'Evidence Page', 'rootspersona' ),
                    "[rootsEvidencePage batchId='1'/]", $options['evidence_page'], 'publish' );
        }

        if ( ! isset( $options['index_page'] )
        || empty( $options['index_page'] ) ) {
            $page = $this->create_page( __( 'Person Index', 'rootspersona' ),
                    '[rootsPersonaIndexPage batchId="1"/]', '', 'publish'  );
            $options['index_page'] = $page;
        } else {
            $this->create_page( __( 'Persona Index', 'rootspersona' ),
                    '[rootsPersonaIndexPage batchId="1"/]', $options['index_page'], 'publish' );
        }

        if ( ! isset( $options['parent_page'] )
        || empty( $options['parent_page'] ) ) {
            $page = $this->create_page( __( 'rootspersona Tree', 'rootspersona' ), '', 'publish' );
            $options['parent_page'] = $page;
        }

        if ( ! isset( $options['is_system_of_record'] )
        || empty( $options['is_system_of_record'] ) ) {
            $options['is_system_of_record'] = 0;
        }

        update_option( 'persona_plugin', $options );
    }

    /**
     *
     * @param string $title
     * @param string $contents
     * @param integer $page
     * @param string $status
     * @return integer
     */
    function create_page( $title, $contents, $page = '', $status = 'private' ) {
        // Create post object
        $my_post = array();
        $my_post['post_title'] = $title;
        $my_post['post_content'] = $contents;
        $my_post['post_status'] = $status;
        $my_post['post_author'] = 0;
        $my_post['post_type'] = 'page';
        $my_post['ping_status'] = 'closed';
        $my_post['comment_status'] = 'closed';
        $my_post['post_parent'] = 0;
        $page_id = '';
        if ( empty( $page ) ) {
            $page_id = wp_insert_post( $my_post );
        } else {
            $my_post['ID'] = $page;
            wp_update_post( $my_post );
            $page_id = $page;
        }
        return $page_id;
    }

    /**
     *
     * @global wpdb $wpdb
     * @param string $prefix
     */
    function persona_uninstall( $prefix ) {
        global $wpdb;

        $options = get_option('persona_plugin');

        $page = $options[ 'index_page' ];
        wp_delete_post( $page );
        $page = $options[ 'evidence_page' ];
        wp_delete_post( $page );

        delete_option( 'rootsPersonaVersion' );
        delete_option( 'rootsDataDir' );
        delete_option( 'rootsEditPage' );
        delete_option( 'rootsCreatePage' );
        delete_option( 'rootsUploadGedcomPage' );
        delete_option( 'rootsIncludePage' );
        delete_option( 'rootsPersonaIndexPage' );
        delete_option( 'rootsUtilityPage' );
        delete_option( 'rootsEvidencePage' );
        delete_option( 'rootsPersonaParentPage' );
        delete_option( 'rootsIsSystemOfRecord' );
        delete_option( 'rootsHideHeader' );
        delete_option( 'rootsHideFacts' );
        delete_option( 'rootsHideAncestors' );
        delete_option( 'rootsHideFamily' );
        delete_option( 'rootsHideFamilyC' );
        delete_option( 'rootsHideFamilyS' );
        delete_option( 'rootsHidePictures' );
        delete_option( 'rootsHideEvidence' );
        delete_option( 'rootsPersonaHideDates' );
        delete_option( 'rootsPersonaHidePlaces' );
        delete_option( 'rootsHideEditLinks' );
        delete_option( 'persona_plugin' );
        $args = array( 'numberposts' => - 1, 'post_type' => 'page', 'post_status' => 'any' );
        $pages = get_posts( $args );
        foreach ( $pages as $page ) {
            if ( preg_match( '/rootsPersona/', $page->post_content ) ) {
                wp_delete_post( $page->ID );
            }
        }

        $creator = new RP_Table_Creator();
        $creator->update_tables( $this->sql_file_to_drop_tables, $prefix );
    }

    /**
     *
     * @return string
     */
    private function get_parent_page() {
        $block =  '<p>' . __('The default WordPress themes display all first level pages by default', 'rootspersona')
             .  '. ' . __('When importing a GEDCOM file, hundreds of pages can be created, all first level pages', 'rootspersona')
             .  '. ' . __('The intent of assigning a Parent Page is to push persona pages down to a second level', 'rootspersona')
             .  ', ' . __('allowing an admin to manage the display of those pages', 'rootspersona')
             .  '.</p/><br\><p>'
             . __('I can not tell you how many new users have skipped the step of assigning the parent page', 'rootspersona')
             .  ', ' . sprintf( __('then emailed me to say that %s has destroyed their site', 'rootspersona'), 'rootpersona')
             .  '. ' . __('(Picture a front page with 100 or more entries in the menu bar)', 'rootspersona')
             .  '.</p/><br\><p>rootspersona '
             . __('will now create and assign a Parent Page by default','rootspersona')
             . '. ' . __('The parent page is NOT meant for display, as the menu is very large on most sites.','rootspersona')
             .  '.</p/><br\><p>';

        return $block;

    }

    /**
     *
     * @param array $options
     * @return string
     */
    public function convert2( $options ) {
       $credentials = new RP_Credentials();
       $data_dir = WP_CONTENT_DIR . 'rootsDataDir';
       $g = new RP_Xml_To_Database_Importer();
       $g->load_tables( $credentials, $data_dir );
       return __('Conversion complete', 'rootspersona') . '.<br/>';
    }
}
?>
