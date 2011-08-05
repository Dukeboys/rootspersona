<?php
/**
 * class RP_that operate on table 'rp_fam_cite'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Fam_Cite_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpFamCiteMySql
	 */
	public function load( $fam_id, $fam_batch_id, $cite_id ) {
		$sql = 'SELECT * FROM rp_fam_cite WHERE fam_id = ?  AND fam_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $cite_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpFamCite primary key
	 */
	public function delete( $fam_id, $fam_batch_id, $cite_id ) {
		$sql = 'DELETE FROM rp_fam_cite WHERE fam_id = ?  AND fam_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $cite_id );
		return $this->execute_update( $sql_query );
	}
    
	/**
	 * Insert record to table
	 *
	 * @param RpFamCiteMySql rpFamCite
	 */
	public function insert( $rp_fam_cite ) {
		$sql = 'INSERT INTO rp_fam_cite (update_datetime, fam_id, fam_batch_id, cite_id) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_cite->update_datetime );
		$sql_query->set_number( $rp_fam_cite->fam_id );
		$sql_query->set_number( $rp_fam_cite->fam_batch_id );
		$sql_query->set_number( $rp_fam_cite->cite_id );
		$this->execute_insert( $sql_query );
		//$rpFamCite->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpFamCiteMySql rpFamCite
	 */
	public function update( $rp_fam_cite ) {
		$sql = 'UPDATE rp_fam_cite SET update_datetime = ? WHERE fam_id = ?  AND fam_batch_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_cite->update_datetime );
		$sql_query->set_number( $rp_fam_cite->fam_id );
		$sql_query->set_number( $rp_fam_cite->fam_batch_id );
		$sql_query->set_number( $rp_fam_cite->cite_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpFamCiteMySql
	 */
	protected function read_row( $row ) {
		$rp_fam_cite = new RP_Fam_Cite();
		$rp_fam_cite->fam_id = $row['fam_id'];
		$rp_fam_cite->fam_batch_id = $row['fam_batch_id'];
		$rp_fam_cite->cite_id = $row['cite_id'];
		$rp_fam_cite->update_datetime = $row['update_datetime'];
		return $rp_fam_cite;
	}

}
?>
