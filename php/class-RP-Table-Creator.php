<?php

class RP_Table_Creator {

    /**
     *
     * @global wpdb $wpdb
     * @param string $sql_file_to_execute
     * @param string $prefix
     */
    function update_tables( $sql_file_to_execute, $prefix ) {
        // read the sql file
        global $wpdb;
        try {
            $f = fopen( $sql_file_to_execute, 'r' );
            if( $f === false) {
                error_log("Unable to open " . $sql_file_to_execute ,0);
                trigger_error("Unable to open " . $sql_file_to_execute, E_USER_ERROR);
                throw new Exception( "Unable to open " . $sql_file_to_execute );
            } else {
                $sql_file = fread( $f, filesize( $sql_file_to_execute ) );
                $sql_array = explode( ';', $sql_file );
                $sql_error_code = 0;
                $sql_error_text = null;
                foreach ( $sql_array as $stmt ) {
                    $stmt = trim( $stmt );
                    if ( strlen( $stmt ) > 3
                        && substr( ltrim( $stmt ), 0, 2 ) != '/*' ) {
                        if ( $prefix != null ) {
                            $stmt = str_replace( 'EXISTS rp_', 'EXISTS ' . $prefix . 'rp_', $stmt );
                            $stmt = str_replace( 'TABLE rp_', 'TABLE ' . $prefix . 'rp_', $stmt );
                            $stmt = str_replace( 'TRUNCATE rp_', 'TRUNCATE ' . $prefix . 'rp_', $stmt );
                            $stmt = str_replace( 'INTO rp_', 'INTO ' . $prefix . 'rp_', $stmt );
                        }
                       $result = $wpdb->query( $stmt );
                        if ( $result === false ) {
                            $wpdb->print_error();
                            throw new Exception( $stmt );
                        }
                    }
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage() . "::" . RP_Persona_Helper::trace_caller(),0);
            trigger_error($e->getMessage(), E_USER_ERROR);
            throw new Exception($e);
        }
    }
}
?>
