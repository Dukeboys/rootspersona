<?php
/**
 * class RP_that operate on table 'rp_indi_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Indi_Note_Mysql_Dao extends Rp_Mysql_DAO{
	
	/**
	 * @todo Description of function deleteByIndiId
	 * @param  $id 
	 * @param  $batchId
	 * @return 
	  */
	public function delete_by_indi_id( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_indi_note WHERE indi_id = ? AND indi_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiNoteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_indi_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndiNote primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_indi_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiNoteMySql rpIndiNote
	 */
	public function insert( $rp_indi_note ) {
		$sql = 'INSERT INTO rp_indi_note (indi_id, indi_batch_id, note, update_datetime) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_note->indi_id );
		$sql_query->set_number( $rp_indi_note->indi_batch_id );
		$sql_query->set( $rp_indi_note->note );
		$sql_query->set( $rp_indi_note->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_indi_note->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiNoteMySql rpIndiNote
	 */
	public function update( $rp_indi_note ) {
		$sql = 'UPDATE rp_indi_note SET indi_id = ?, indi_batch_id = ?, note = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_note->indi_id );
		$sql_query->set_number( $rp_indi_note->indi_batch_id );
		$sql_query->set( $rp_indi_note->note );
		$sql_query->set( $rp_indi_note->update_datetime );
		$sql_query->set_number( $rp_indi_note->id );
		return $this->execute_update( $sql_query );
	}
	
	/**
	 * Read row
	 *
	 * @return RpIndiNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_indi_note = new RP_Indi_Note();
		$rp_indi_note->id = $row['id'];
		$rp_indi_note->indi_id = $row['indi_id'];
		$rp_indi_note->indi_batch_id = $row['indi_batch_id'];
		$rp_indi_note->note = $row['note'];
		$rp_indi_note->update_datetime = $row['update_datetime'];
		return $rp_indi_note;
	}

}
?>
