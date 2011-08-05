<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();
require_once WP_PLUGIN_DIR  . '/rootspersona/php/class-RP-Persona-Installer.php';
require_once WP_PLUGIN_DIR  . '/rootspersona/php/class-RP-Table-Creator.php';
require_once WP_PLUGIN_DIR  . '/rootspersona/php/dao/sql/class-RP-Credentials.php';
global $wpdb;
$installer = new RP_Persona_Installer();
$installer->persona_uninstall($wpdb->prefix);


