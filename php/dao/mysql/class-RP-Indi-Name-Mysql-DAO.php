<?php
/**
 * class RP_that operate on table 'rp_indi_name'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Indi_Name_Mysql_Dao extends Rp_Mysql_DAO {
		/**
	 * @todo Description of function deleteByIndiId
	 * @param  $indiId
	 * @param  $indiBatchId
	 * @return
	 */
	public function delete_by_indi_id( $indi_id, $indi_batch_id ) {
        $sql = 'DELETE FROM rp_name_personal WHERE id IN'
             . ' (SELECT name_id AS id'
             . ' FROM rp_indi_name WHERE indi_id = ?  AND indi_batch_id = ?)';
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$this->execute_update( $sql_query );

		$sql = 'DELETE FROM rp_indi_name WHERE indi_id = ?  AND indi_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$this->execute_update( $sql_query );
	}


	/**
	 * @todo Description of function loadList
	 * @param  $indiId
	 * @param  $indiBatchId
	 * @return
	 */
	public function load_list( $indi_id, $indi_batch_id ) {
		$sql = 'SELECT * FROM rp_indi_name WHERE indi_id = ?  AND indi_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->get_list( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiNameMySql
	 */
	public function load( $indi_id, $indi_batch_id, $name_id ) {
		$sql = 'SELECT * FROM rp_indi_name WHERE indi_id = ?  AND indi_batch_id = ?  AND name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $name_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndiName primary key
	 */
	public function delete( $indi_id, $indi_batch_id, $name_id ) {
		$sql = 'DELETE FROM rp_indi_name WHERE indi_id = ?  AND indi_batch_id = ?  AND name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $name_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiNameMySql rpIndiName
	 */
	public function insert( $rp_indi_name ) {
		$sql = 'INSERT INTO rp_indi_name (update_datetime, indi_id, indi_batch_id, name_id, seq_nbr) VALUES (now(), ?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_name->indi_id );
		$sql_query->set_number( $rp_indi_name->indi_batch_id );
		$sql_query->set_number( $rp_indi_name->name_id );
		$sql_query->set_number( $rp_indi_name->seq_nbr );
		$this->execute_insert( $sql_query );
		//$rpIndiName->id = $id;
		//return $id;

	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiNameMySql rpIndiName
	 */
	public function update( $rp_indi_name ) {
		$sql = 'UPDATE rp_indi_name SET seq_nbr = ?, update_datetime = now() WHERE indi_id = ?  AND indi_batch_id = ?  AND name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_indi_name->seq_nbr );
		$sql_query->set( $rp_indi_name->indi_id );
		$sql_query->set_number( $rp_indi_name->indi_batch_id );
		$sql_query->set_number( $rp_indi_name->name_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpIndiNameMySql
	 */
	protected function read_row( $row ) {
		$rp_indi_name = new RP_Indi_Name();
		$rp_indi_name->indi_id = $row['indi_id'];
		$rp_indi_name->indi_batch_id = $row['indi_batch_id'];
		$rp_indi_name->name_id = $row['name_id'];
		$rp_indi_name->seq_nbr = $row['seq_nbr'];
		$rp_indi_name->update_datetime = $row['update_datetime'];
		return $rp_indi_name;
	}

}
?>
