<?php

class RP_Evidence_Factory {

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
     * @param string $id
     * @param integer $batch_id
     * @param array $options
     * @return RP_Evidence
     */
	public function get_with_options( $id, $batch_id, $options ) {
		$transaction = new RP_Transaction( $this->credentials, true );
        $evi = RP_Dao_Factory::get_rp_source_dao($this->credentials->prefix)
                ->get_source($id, $batch_id, $options);
		$transaction->close();
		return $evi;
	}

    /**
     *
     * @param integer $batch_id
     * @param array $options
     * @return array
     */
    public function get_index_with_options( $batch_id, $options ) {
		$transaction = new RP_Transaction( $this->credentials, true );
        $index = RP_Dao_Factory::get_rp_persona_dao( $this->credentials->prefix )
                ->get_indexed_sources( $batch_id, $options['page_nbr'], $options['per_page'] );
        $transaction->close();
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
                ->get_indexed_source_cnt( $batch_id );
        $transaction->close();
        return $cnt;
    }
}
?>
