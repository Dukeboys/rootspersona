<?php
require_once ( WP_PLUGIN_DIR . '/rootspersona/php/class-RP-Persona-Helper.php' );

class RP_Index_Factory {
    /**
     *
     * @var RP_Credentials
     */
    var $credentials;

    /**
     *
     * @param RP_Credentials $credentials
     */
    public function __construct( $credentials ) {
        $this->credentials = $credentials;
    }

    /**
     *
     * @param integer $batch_id
     * @param array $options
     * @return array
     */
    public function get_with_options( $batch_id, $options ) {
        $transaction = new RP_Transaction( $this->credentials, true );
        $rows = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_indexed_page( $batch_id, $options['surname'], 
                                    $options['page_nbr'], $options['per_page'],
                                    $options['style'] );
        $transaction->close();
        $cnt = count( $rows );
        $uscore = $options['uscore'];
        $index = array();
        for ( $idx = 0;	$idx < $cnt; $idx++ ) {
            $persona = $rows[$idx];
            RP_Persona_Helper::score_persona( $persona, $options );
            if ( ! RP_Persona_Helper::is_restricted( $uscore, $persona->pscore ) ) {
                $index[] = $persona;
            }
        }
        return $index;
    }

    /**
     *
     * @param integer $batch_id
     * @param array $options
     * @return integer
     */
    public function get_cnt( $batch_id, $options ) {
        $transaction = new RP_Transaction( $this->credentials, true );
        $cnt = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_indexed_page_cnt( $batch_id, $options['surname'] );
        // @todo we are adjusted for Exc, but not for Pvt or Mbr
        $transaction->close();
        return $cnt;
    }
}
?>
