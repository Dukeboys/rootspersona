<?php
/**
 * class RP_that operate on table 'rp_event_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Event_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpEventNoteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_event_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

    /**
	 * Delete record FROM table
	 * @param rpEventNote primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_event_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
    
	/**
	 * Insert record to table
	 *
	 * @param RpEventNoteMySql rpEventNote
	 */
	public function insert( $rp_event_note ) {
		$sql = 'INSERT INTO rp_event_note (event_id, note, update_datetime) VALUES (?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_event_note->event_id );
		$sql_query->set( $rp_event_note->note );
		$sql_query->set_number( $rp_event_note->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_event_note->id = $id;return $id;
	}
    
	/**
	 * Update record in table
	 *
	 * @param RpEventNoteMySql rpEventNote
	 */
	public function update( $rp_event_note ) {
		$sql = 'UPDATE rp_event_note SET event_id = ?, note = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_event_note->event_id );
		$sql_query->set( $rp_event_note->note );
		$sql_query->set_number( $rp_event_note->update_datetime );
		$sql_query->set_number( $rp_event_note->id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Read row
	 *
	 * @return RpEventNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_event_note = new RP_Event_Note();
		$rp_event_note->id = $row['id'];
		$rp_event_note->event_id = $row['event_id'];
		$rp_event_note->note = $row['note'];
		$rp_event_note->update_datetime = $row['update_datetime'];
		return $rp_event_note;
	}
}
?>
