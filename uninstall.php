<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();
require_once WP_PLUGIN_DIR  . '/rootspersona/php/class-RP-Persona-Installer.php';
require_once WP_PLUGIN_DIR  . '/rootspersona/php/class-RP-Table-Creator.php';
require_once WP_PLUGIN_DIR  . '/rootspersona/php/dao/sql/class-RP-Credentials.php';
global $wpdb;
$installer = new RP_Persona_Installer();

if (function_exists('is_multisite') && is_multisite()) {
    // check if it is a network activation - if so, run the activation function for each blog id
    if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
        $old_blog = $wpdb->blogid;
        // Get all blog ids
        $blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
        foreach ($blogids as $blog_id) {
            switch_to_blog($blog_id);
            $installer->persona_uninstall($wpdb->prefix);
        }
        switch_to_blog($old_blog);
        return;
    }
}
$installer->persona_uninstall($wpdb->prefix);
