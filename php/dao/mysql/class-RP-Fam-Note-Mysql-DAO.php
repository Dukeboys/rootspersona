<?php
/**
 * class RP_that operate on table 'rp_fam_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-06 07:37
 */
class RP_Fam_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpFamNoteMySql
	 */
	public function load( $fam_id, $fam_batch_id, $note_id ) {
		$sql = 'SELECT * FROM rp_fam_note WHERE fam_id = ?  AND fam_batch_id = ?  AND note_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $note_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpFamNote primary key
	 */
	public function delete( $fam_id, $fam_batch_id, $note_id ) {
		$sql = 'DELETE FROM rp_fam_note WHERE fam_id = ?  AND fam_batch_id = ?  AND note_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $note_id );
		return $this->execute_update( $sql_query );
	}
    
	/**
	 * Insert record to table
	 *
	 * @param RpFamNoteMySql rpFamNote
	 */
	public function insert( $rp_fam_note ) {
		$sql = 'INSERT INTO rp_fam_note (update_datetime, fam_id, fam_batch_id, note_id) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_note->update_datetime );
		$sql_query->set_number( $rp_fam_note->fam_id );
		$sql_query->set_number( $rp_fam_note->fam_batch_id );
		$sql_query->set_number( $rp_fam_note->note_id );
		$this->execute_insert( $sql_query );
		//$rpFamNote->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpFamNoteMySql rpFamNote
	 */
	public function update( $rp_fam_note ) {
		$sql = 'UPDATE rp_fam_note SET update_datetime = ? WHERE fam_id = ?  AND fam_batch_id = ?  AND note_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_note->update_datetime );
		$sql_query->set_number( $rp_fam_note->fam_id );
		$sql_query->set_number( $rp_fam_note->fam_batch_id );
		$sql_query->set_number( $rp_fam_note->note_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Read row
	 *
	 * @return RpFamNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_fam_note = new RP_Fam_Note();
		$rp_fam_note->fam_id = $row['fam_id'];
		$rp_fam_note->fam_batch_id = $row['fam_batch_id'];
		$rp_fam_note->note_id = $row['note_id'];
		$rp_fam_note->update_datetime = $row['update_datetime'];
		return $rp_fam_note;
	}
}
?>
