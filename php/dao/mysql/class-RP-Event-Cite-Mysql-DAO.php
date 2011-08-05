<?php
/**
 * class RP_that operate on table 'rp_event_cite'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Event_Cite_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpEventCiteMySql
	 */
	public function load( $event_id, $cite_id ) {
		$sql = 'SELECT * FROM rp_event_cite WHERE event_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $event_id );
		$sql_query->set_number( $cite_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpEventCite primary key
	 */
	public function delete( $event_id, $cite_id ) {
		$sql = 'DELETE FROM rp_event_cite WHERE event_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $event_id );
		$sql_query->set_number( $cite_id );
		return $this->execute_update( $sql_query );
	}
    
    public function delete_by_event_id( $event_id ) {
		$sql = 'DELETE FROM rp_event_cite WHERE event_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $event_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpEventCiteMySql rpEventCite
	 */
	public function insert( $rp_event_cite ) {
		$sql = 'INSERT INTO rp_event_cite (update_datetime, event_id, cite_id) VALUES (now(), ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_event_cite->event_id );
		$sql_query->set_number( $rp_event_cite->cite_id );
		$this->execute_insert( $sql_query );
	}
	/**
	 * Update record in table
	 *
	 * @param RpEventCiteMySql rpEventCite
	 */
	public function update( $rp_event_cite ) {
		$sql = 'UPDATE rp_event_cite SET update_datetime = now() WHERE event_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_event_cite->event_id );
		$sql_query->set_number( $rp_event_cite->cite_id );
		return $this->execute_update( $sql_query );
	}

	protected function read_row( $row ) {
		$rp_event_cite = new RP_Event_Cite();
		$rp_event_cite->event_id = $row['event_id'];
		$rp_event_cite->cite_id = $row['cite_id'];
		$rp_event_cite->update_datetime = $row['update_datetime'];
		return $rp_event_cite;
	}
}
?>
