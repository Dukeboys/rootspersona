<?php
/**
 * class RP_that operate on table 'rp_indi_fam'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-06 07:37
 */
class RP_Indi_Fam_Mysql_Dao extends Rp_Mysql_DAO {
		/**
	 * @todo Description of function deleteByIndi
	 * @param  $indiId 
	 * @param  $indiBatchId
	 * @return 
	 */
	public function delete_by_indi( $indi_id, $indi_batch_id ) {
		$sql = 'DELETE FROM rp_indi_fam WHERE indi_id = ?  AND indi_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpIndiFamMySql
	 */
	public function load( $indi_id, $indi_batch_id, $fam_id, $fam_batch_id, $link_type ) {
		$sql = 'SELECT * FROM rp_indi_fam WHERE indi_id = ?  AND indi_batch_id = ?  AND fam_id = ?  AND fam_batch_id = ?  AND link_type = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $link_type );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpIndiFam primary key
	 */
	public function delete( $indi_id, $indi_batch_id, $fam_id, $fam_batch_id, $link_type ) {
		$sql = 'DELETE FROM rp_indi_fam WHERE indi_id = ?  AND indi_batch_id = ?  AND fam_id = ?  AND fam_batch_id = ?  AND link_type = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $indi_id );
		$sql_query->set_number( $indi_batch_id );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $link_type );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpIndiFamMySql rpIndiFam
	 */
	public function insert( $rp_indi_fam ) {
		$sql = 'INSERT INTO rp_indi_fam (link_status, pedigree, update_datetime, indi_id, indi_batch_id, fam_id, fam_batch_id, link_type) VALUES (?, ?, now(), ?, ?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_fam->link_status );
		$sql_query->set( $rp_indi_fam->pedigree );
		$sql_query->set( $rp_indi_fam->indi_id );
		$sql_query->set_number( $rp_indi_fam->indi_batch_id );
		$sql_query->set( $rp_indi_fam->fam_id );
		$sql_query->set_number( $rp_indi_fam->fam_batch_id );
		$sql_query->set( $rp_indi_fam->link_type );
		$this->execute_insert( $sql_query );
		//$rpIndiFam->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpIndiFamMySql rpIndiFam
	 */
	public function update( $rp_indi_fam ) {
		$sql = 'UPDATE rp_indi_fam SET link_status = ?, pedigree = ?, update_datetime = ? WHERE indi_id = ?  AND indi_batch_id = ?  AND fam_id = ?  AND fam_batch_id = ?  AND link_type = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_indi_fam->link_status );
		$sql_query->set( $rp_indi_fam->pedigree );
		$sql_query->set( $rp_indi_fam->indi_id );
		$sql_query->set_number( $rp_indi_fam->indi_batch_id );
		$sql_query->set( $rp_indi_fam->fam_id );
		$sql_query->set_number( $rp_indi_fam->fam_batch_id );
		$sql_query->set( $rp_indi_fam->link_type );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpIndiFamMySql
	 */
	protected function read_row( $row ) {
		$rp_indi_fam = new RP_Indi_Fam();
		$rp_indi_fam->indi_id = $row['indi_id'];
		$rp_indi_fam->indi_batch_id = $row['indi_batch_id'];
		$rp_indi_fam->fam_id = $row['fam_id'];
		$rp_indi_fam->fam_batch_id = $row['fam_batch_id'];
		$rp_indi_fam->link_type = $row['link_type'];
		$rp_indi_fam->link_status = $row['link_status'];
		$rp_indi_fam->pedigree = $row['pedigree'];
		$rp_indi_fam->update_datetime = $row['update_datetime'];
		return $rp_indi_fam;
	}

}
?>
