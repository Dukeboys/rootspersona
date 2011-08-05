<?php
/**
 * class RP_that operate on table 'rp_indi_cite'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Indi_Cite_Mysql_Dao extends Rp_Mysql_DAO {
		/**
	 * @todo Description of function deleteByIndiId
	 * @param  $indiId 
	 * @param  $indiBatchId
	 * @return 
	 */
	public function delete_by_indi_id( $indi_id, $indi_batch_id ) {
		$sql = 'DELETE FROM rp_indi_cite WHERE indi_id = ?  AND indi_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiCiteMySql
	 */
	public function load( $indi_id, $indi_batch_id, $cite_id ) {
		$sql = 'SELECT * FROM rp_indi_cite WHERE indi_id = ?  AND indi_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $cite_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndiCite primary key
	 */
	public function delete( $indi_id, $indi_batch_id, $cite_id ) {
		$sql = 'DELETE FROM rp_indi_cite WHERE indi_id = ?  AND indi_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set_number( $cite_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiCiteMySql rpIndiCite
	 */
	public function insert( $rp_indi_cite ) {
		$sql = 'INSERT INTO rp_indi_cite (update_datetime, indi_id, indi_batch_id, cite_id) VALUES (now(), ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_cite->indi_id );
		$sql_query->set_number( $rp_indi_cite->indi_batch_id );
		$sql_query->set_number( $rp_indi_cite->cite_id );
		$this->execute_insert( $sql_query );
	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiCiteMySql rpIndiCite
	 */
	public function update( $rp_indi_cite ) {
		$sql = 'UPDATE rp_indi_cite SET update_datetime = now() WHERE indi_id = ?  AND indi_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_cite->indi_id );
		$sql_query->set_number( $rp_indi_cite->indi_batch_id );
		$sql_query->set_number( $rp_indi_cite->cite_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Read row
	 *
	 * @return RpIndiCiteMySql
	 */
	protected function read_row( $row ) {
		$rp_indi_cite = new RP_Indi_Cite();
		$rp_indi_cite->indi_id = $row['indi_id'];
		$rp_indi_cite->indi_batch_id = $row['indi_batch_id'];
		$rp_indi_cite->cite_id = $row['cite_id'];
		$rp_indi_cite->update_datetime = $row['update_datetime'];
		return $rp_indi_cite;
	}

}
?>
