<?php
/**
 * class RP_that operate on table 'rp_indi_event'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-03 21:25
 */
class RP_Indi_Event_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * @todo Description of function loadList
	 * @param  $indiId
	 * @param  $indiBatchId
	 * @return
	 */
	public function load_list( $indi_id, $indi_batch_id ) {
		$sql = 'SELECT * FROM rp_indi_event WHERE indi_id = ?  AND indi_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->get_list( $sql_query );
	}


	/**
	 * @todo Description of function deleteByIndiId
	 * @param  $indiId
	 * @param  $indiBatchId
	 * @return
	 */
	public function delete_by_indi_id( $indi_id, $indi_batch_id ) {
		$sql = 'DELETE FROM rp_indi_event WHERE indi_id = ?  AND indi_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiEventMySql
	 */
	public function load( $indi_id, $indi_batch_id, $event_id ) {
		$sql = 'SELECT * FROM rp_indi_event WHERE indi_id = ?  AND indi_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $event_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndiEvent primary key
	 */
	public function delete( $indi_id, $indi_batch_id, $event_id ) {
		$sql = 'DELETE FROM rp_indi_event WHERE indi_id = ?  AND indi_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $event_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiEventMySql rpIndiEvent
	 */
	public function insert( $rp_indi_event ) {
		$sql = 'INSERT INTO rp_indi_event (update_datetime, indi_id, indi_batch_id, event_id) VALUES (now(), ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_event->indi_id );
		$sql_query->set_number( $rp_indi_event->indi_batch_id );
		$sql_query->set_number( $rp_indi_event->event_id );
		$this->execute_insert( $sql_query );
	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiEventMySql rpIndiEvent
	 */
	public function update( $rp_indi_event ) {
		$sql = 'UPDATE rp_indi_event SET update_datetime = now() WHERE indi_id = ?  AND indi_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_event->indi_id );
		$sql_query->set_number( $rp_indi_event->indi_batch_id );
		$sql_query->set_number( $rp_indi_event->event_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpIndiEventMySql
	 */
	protected function read_row( $row ) {
		$rp_indi_event = new RP_Indi_Event();
		$rp_indi_event->indi_id = $row['indi_id'];
		$rp_indi_event->indi_batch_id = $row['indi_batch_id'];
		$rp_indi_event->event_id = $row['event_id'];
		$rp_indi_event->update_datetime = $row['update_datetime'];
		return $rp_indi_event;
	}

}
?>
