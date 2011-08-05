<?php
/**
 * class RP_that operate on table 'rp_event_detail'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Event_Detail_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpEventDetailMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_event_detail WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpEventDetail primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_event_detail WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpEventDetailMySql rpEventDetail
	 */
	public function insert( $rp_event_detail ) {
		$sql = 'INSERT INTO rp_event_detail (event_type, classification, event_date, place, addr_id, resp_agency, religious_aff, cause, restriction_notice, update_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, now())';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_event_detail->event_type );
		$sql_query->set( $rp_event_detail->classification );
		$sql_query->set( $rp_event_detail->event_date );
		$sql_query->set( $rp_event_detail->place );
		$sql_query->set_number( $rp_event_detail->addr_id );
		$sql_query->set( $rp_event_detail->resp_agency );
		$sql_query->set( $rp_event_detail->religious_aff );
		$sql_query->set( $rp_event_detail->cause );
		$sql_query->set( $rp_event_detail->restriction_notice );
		$id = $this->execute_insert( $sql_query );
		$rp_event_detail->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpEventDetailMySql rpEventDetail
	 */
	public function update( $rp_event_detail ) {
		$sql = 'UPDATE rp_event_detail SET event_type = ?, classification = ?, event_date = ?, place = ?, addr_id = ?, resp_agency = ?, religious_aff = ?, cause = ?, restriction_notice = ?, update_datetime = now() WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_event_detail->event_type );
		$sql_query->set( $rp_event_detail->classification );
		$sql_query->set( $rp_event_detail->event_date );
		$sql_query->set( $rp_event_detail->place );
		$sql_query->set_number( $rp_event_detail->addr_id );
		$sql_query->set( $rp_event_detail->resp_agency );
		$sql_query->set( $rp_event_detail->religious_aff );
		$sql_query->set( $rp_event_detail->cause );
		$sql_query->set( $rp_event_detail->restriction_notice );
		$sql_query->set_number( $rp_event_detail->id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpEventDetailMySql
	 */
	protected function read_row( $row ) {
		$rp_event_detail = new RP_Event_Detail();
		$rp_event_detail->id = $row['id'];
		$rp_event_detail->event_type = $row['event_type'];
		$rp_event_detail->classification = $row['classification'];
		$rp_event_detail->event_date = $row['event_date'];
		$rp_event_detail->place = $row['place'];
		$rp_event_detail->addr_id = $row['addr_id'];
		$rp_event_detail->resp_agency = $row['resp_agency'];
		$rp_event_detail->religious_aff = $row['religious_aff'];
		$rp_event_detail->cause = $row['cause'];
		$rp_event_detail->restriction_notice = $row['restriction_notice'];
		$rp_event_detail->update_datetime = $row['update_datetime'];
		return $rp_event_detail;
	}
}
?>
