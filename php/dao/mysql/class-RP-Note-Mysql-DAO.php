<?php
/**
 * class RP_that operate on table 'rp_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpNoteMySql
	 */
	public function load( $id, $batch_id = '1' ) {
		$sql = 'SELECT * FROM rp_note WHERE id = ? AND batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpNote primary key
	 */
	public function delete( $id, $batch_id = '1' ) {
		$sql = 'DELETE FROM rp_note WHERE id = ? AND batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
        $sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpNoteMySql rpNote
	 */
	public function insert( $rp_note ) {
		$sql = 'INSERT INTO rp_note (id, batch_id, cite_id, auto_rec_id, ged_change_date, update_datetime, submitter_text) VALUES (?, ?, ?, ?, ?, now(), ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_note->id );
		$sql_query->set_number( $rp_note->batch_id );
		$sql_query->set_number( $rp_note->cite_id );
		$sql_query->set( $rp_note->auto_rec_id );
		$sql_query->set( $rp_note->ged_change_date );
		$sql_query->set( $rp_note->update_datetime );
		$sql_query->set( $rp_note->submitter_text );
		$id = $this->execute_insert( $sql_query );
		$rp_note->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpNoteMySql rpNote
	 */
	public function update( $rp_note ) {
		$sql = 'UPDATE rp_note SET cite_id = ?, auto_rec_id = ?, ged_change_date = ?, update_datetime = now(), submitter_text = ? WHERE id = ? AND batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_note->cite_id );
		$sql_query->set( $rp_note->auto_rec_id );
		$sql_query->set( $rp_note->ged_change_date );
		$sql_query->set( $rp_note->submitter_text );
		$sql_query->set( $rp_note->id );
        $sql_query->set_number( $rp_note->batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_note = new RP_Note();
		$rp_note->id = $row['id'];
        $rp_note->batch_id = '1';
		$rp_note->cite_id = $row['cite_id'];
		$rp_note->auto_rec_id = $row['auto_rec_id'];
		$rp_note->ged_change_date = $row['ged_change_date'];
		$rp_note->submitter_text = $row['submitter_text'];
		return $rp_note;
	}

}
?>
