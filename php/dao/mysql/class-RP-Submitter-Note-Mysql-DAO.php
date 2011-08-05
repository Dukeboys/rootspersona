<?php
/**
 * class RP_that operate on table 'rp_submitter_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Submitter_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpSubmitterNoteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_submitter_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpSubmitterNote primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_submitter_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpSubmitterNoteMySql rpSubmitterNote
	 */
	public function insert( $rp_submitter_note ) {
		$sql = 'INSERT INTO rp_submitter_note (submitter_id, submitter_batch_id, note, update_datetime) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_submitter_note->submitter_id );
		$sql_query->set_number( $rp_submitter_note->submitter_batch_id );
		$sql_query->set( $rp_submitter_note->note );
		$sql_query->set( $rp_submitter_note->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_submitter_note->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpSubmitterNoteMySql rpSubmitterNote
	 */
	public function update( $rp_submitter_note ) {
		$sql = 'UPDATE rp_submitter_note SET submitter_id = ?, submitter_batch_id = ?, note = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_submitter_note->submitter_id );
		$sql_query->set_number( $rp_submitter_note->submitter_batch_id );
		$sql_query->set( $rp_submitter_note->note );
		$sql_query->set( $rp_submitter_note->update_datetime );
		$sql_query->set_number( $rp_submitter_note->id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpSubmitterNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_submitter_note = new RP_Submitter_Note();
		$rp_submitter_note->id = $row['id'];
		$rp_submitter_note->submitter_id = $row['submitter_id'];
		$rp_submitter_note->submitter_batch_id = $row['submitter_batch_id'];
		$rp_submitter_note->note = $row['note'];
		$rp_submitter_note->update_datetime = $row['update_datetime'];
		return $rp_submitter_note;
	}

}
?>
