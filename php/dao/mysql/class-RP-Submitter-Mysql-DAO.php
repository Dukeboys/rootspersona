<?php
/**
 * class RP_that operate on table 'rp_submitter'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Submitter_Mysql_Dao extends Rp_Mysql_DAO  {
		/**
	 * @todo Description of function deleteByBatchId
	 * @param  $batchId
	 * @return 
	 */
	public function delete_by_batch_id( $batch_id ) {
		$sql = 'DELETE FROM rp_submitter WHERE batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpSubmitterMySql
	 */
	public function load( $id, $batch_id ) {
		$sql = 'SELECT * FROM rp_submitter WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpSubmitter primary key
	 */
	public function delete( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_submitter WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpSubmitterMySql rpSubmitter
	 */
	public function insert( $rp_submitter ) {
		$sql = 'INSERT INTO rp_submitter (submitter_name, addr_id, lang1, lang2, lang3, registered_rfn, auto_rec_id, ged_change_date, update_datetime, id, batch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_submitter->submitter_name );
		$sql_query->set_number( $rp_submitter->addr_id );
		$sql_query->set( $rp_submitter->lang1 );
		$sql_query->set( $rp_submitter->lang2 );
		$sql_query->set( $rp_submitter->lang3 );
		$sql_query->set( $rp_submitter->registered_rfn );
		$sql_query->set( $rp_submitter->auto_rec_id );
		$sql_query->set( $rp_submitter->ged_change_date );
		$sql_query->set( $rp_submitter->id );
		$sql_query->set_number( $rp_submitter->batch_id );
		$this->execute_insert( $sql_query );
		//$rpSubmitter->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpSubmitterMySql rpSubmitter
	 */
	public function update( $rp_submitter ) {
		$sql = 'UPDATE rp_submitter SET submitter_name = ?, addr_id = ?, lang1 = ?, lang2 = ?, lang3 = ?, registered_rfn = ?, auto_rec_id = ?, ged_change_date = ?, update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_submitter->submitter_name );
		$sql_query->set_number( $rp_submitter->addr_id );
		$sql_query->set( $rp_submitter->lang1 );
		$sql_query->set( $rp_submitter->lang2 );
		$sql_query->set( $rp_submitter->lang3 );
		$sql_query->set( $rp_submitter->registered_rfn );
		$sql_query->set( $rp_submitter->auto_rec_id );
		$sql_query->set( $rp_submitter->ged_change_date );
		$sql_query->set( $rp_submitter->id );
		$sql_query->set_number( $rp_submitter->batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpSubmitterMySql
	 */
	protected function read_row( $row ) {
		$rp_submitter = new RP_Submitter();
		$rp_submitter->id = $row['id'];
		$rp_submitter->batch_id = $row['batch_id'];
		$rp_submitter->submitter_name = $row['submitter_name'];
		$rp_submitter->addr_id = $row['addr_id'];
		$rp_submitter->lang1 = $row['lang1'];
		$rp_submitter->lang2 = $row['lang2'];
		$rp_submitter->lang3 = $row['lang3'];
		$rp_submitter->registered_rfn = $row['registered_rfn'];
		$rp_submitter->auto_rec_id = $row['auto_rec_id'];
		$rp_submitter->ged_change_date = $row['ged_change_date'];
		$rp_submitter->update_datetime = $row['update_datetime'];
		return $rp_submitter;
	}
	

}
?>
