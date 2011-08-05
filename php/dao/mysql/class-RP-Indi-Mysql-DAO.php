<?php
/**
 * class RP_that operate on table 'rp_indi'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Indi_Mysql_Dao extends Rp_Mysql_DAO {
		/**
	 * @todo Description of function updatePage
	 * @param  $id
	 * @param  $batchId
	 * @param  $pageId
	 * @return
	 */
	public function update_page( $id, $batch_id, $page_id ) {
		$sql = 'UPDATE rp_indi SET wp_page_id = ?, update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $page_id );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}


	/**
	 * @todo Description of function unlinkAllPages
	 * @param
	 * @return
	 */
	public function unlink_all_pages() {
		$sql = 'UPDATE rp_indi SET wp_page_id = null, update_datetime = now()';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->execute_update( $sql_query );
	}


	/**
	 * @todo Description of function getPageId
	 * @param  $id
	 * @param  $batchId
	 * @return
	 */
	public function get_page_id( $id, $batch_id ) {
		$sql = 'SELECT wp_page_id FROM rp_indi WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->query_single_result( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiMySql
	 */
	public function load( $id, $batch_id ) {
		$sql = 'SELECT * FROM rp_indi WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndi primary key
	 */
	public function delete( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_indi WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiMySql rpIndi
	 */
	public function insert( $rp_indi ) {
		$sql = 'INSERT INTO rp_indi (restriction_notice, gender, perm_rec_file_nbr, anc_rec_file_nbr, auto_rec_id, ged_change_date, update_datetime, id, batch_id) VALUES (?, ?, ?, ?, ?, ?, now(), ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi->restriction_notice );
		$sql_query->set( $rp_indi->gender );
		$sql_query->set( $rp_indi->perm_rec_file_nbr );
		$sql_query->set( $rp_indi->anc_rec_file_nbr );
		$sql_query->set( $rp_indi->auto_rec_id );
		$sql_query->set( $rp_indi->ged_change_date );
		$sql_query->set( $rp_indi->id );
		$sql_query->set_number( $rp_indi->batch_id );
		$this->execute_insert( $sql_query );
		//$rpIndi->id = $id;
		//return $id;

	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiMySql rpIndi
	 */
	public function update( $rp_indi ) {
		$sql = 'UPDATE rp_indi SET restriction_notice = ?, gender = ?, perm_rec_file_nbr = ?, anc_rec_file_nbr = ?, auto_rec_id = ?, ged_change_date = ?, update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi->restriction_notice );
		$sql_query->set( $rp_indi->gender );
		$sql_query->set( $rp_indi->perm_rec_file_nbr );
		$sql_query->set( $rp_indi->anc_rec_file_nbr );
		$sql_query->set( $rp_indi->auto_rec_id );
		$sql_query->set( $rp_indi->ged_change_date );
		$sql_query->set( $rp_indi->id );
		$sql_query->set_number( $rp_indi->batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Read row
	 *
	 * @return RpIndiMySql
	 */
	protected function read_row( $row ) {
		$rp_indi = new RP_Indi();
		$rp_indi->id = $row['id'];
		$rp_indi->batch_id = $row['batch_id'];
		$rp_indi->wp_page_id = $row['wp_page_id'];
		$rp_indi->restriction_notice = $row['restriction_notice'];
		$rp_indi->gender = $row['gender'];
		$rp_indi->perm_rec_file_nbr = $row['perm_rec_file_nbr'];
		$rp_indi->anc_rec_file_nbr = $row['anc_rec_file_nbr'];
		$rp_indi->auto_rec_id = $row['auto_rec_id'];
		$rp_indi->ged_change_date = $row['ged_change_date'];
		$rp_indi->update_datetime = $row['update_datetime'];
		return $rp_indi;
	}

}
?>
