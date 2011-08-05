<?php
/**
 * class RP_that operate on table 'rp_fam_event'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-03 21:25
 */
class RP_Fam_Event_Mysql_Dao extends Rp_Mysql_DAO {
		

	/**
	 * @todo Description of function loadList
	 * @param  $famId 
	 * @param  $famBatchId
	 * @return 
	 */
	public function load_list( $fam_id, $fam_batch_id ) {
		$sql = 'SELECT * FROM rp_fam_event WHERE fam_id = ?  AND fam_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		return $this->get_list( $sql_query );
	}
	

	/**
	 * @todo Description of function deleteByFam
	 * @param  $famId 
	 * @param  $famBatchId
	 * @return 
	 */
	public function delete_by_fam( $fam_id, $fam_batch_id ) {
		$sql = 'DELETE FROM rp_fam_event WHERE fam_id = ?  AND fam_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpFamEventMySql
	 */
	public function load( $fam_id, $fam_batch_id, $event_id ) {
		$sql = 'SELECT * FROM rp_fam_event WHERE fam_id = ?  AND fam_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $event_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpFamEvent primary key
	 */
	public function delete( $fam_id, $fam_batch_id, $event_id ) {
		$sql = 'DELETE FROM rp_fam_event WHERE fam_id = ?  AND fam_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $event_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpFamEventMySql rpFamEvent
	 */
	public function insert( $rp_fam_event ) {
		$sql = 'INSERT INTO rp_fam_event (update_datetime, fam_id, fam_batch_id, event_id) VALUES (now(), ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_event->fam_id );
		$sql_query->set_number( $rp_fam_event->fam_batch_id );
		$sql_query->set_number( $rp_fam_event->event_id );
		$this->execute_insert( $sql_query );
	}
	/**
	 * Update record in table
	 *
	 * @param RpFamEventMySql rpFamEvent
	 */
	public function update( $rp_fam_event ) {
		$sql = 'UPDATE rp_fam_event SET update_datetime = now() WHERE fam_id = ?  AND fam_batch_id = ?  AND event_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_event->fam_id );
		$sql_query->set_number( $rp_fam_event->fam_batch_id );
		$sql_query->set_number( $rp_fam_event->event_id );
		return $this->execute_update( $sql_query );
	}

    /**
	 * Read row
	 *
	 * @return RpFamEventMySql
	 */
	protected function read_row( $row ) {
		$rp_fam_event = new RP_Fam_Event();
		$rp_fam_event->fam_id = $row['fam_id'];
		$rp_fam_event->fam_batch_id = $row['fam_batch_id'];
		$rp_fam_event->event_id = $row['event_id'];
		$rp_fam_event->update_datetime = $row['update_datetime'];
		return $rp_fam_event;
	}

}
?>
