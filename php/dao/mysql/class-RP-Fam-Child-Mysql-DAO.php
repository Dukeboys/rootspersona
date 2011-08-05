<?php
/**
 * class RP_that operate on table 'rp_fam_child'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Fam_Child_Mysql_Dao extends Rp_Mysql_DAO {
	

	/**
	 * @todo Description of function loadChildren
	 * @param  $famId 
	 * @param  $famBatchId
	 * @return 
	 */
	public function load_children( $fam_id, $fam_batch_id ) {
		$sql = 'SELECT * FROM rp_fam_child WHERE fam_id = ?  AND fam_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		return $this->get_list( $sql_query );
	}
	

	/**
	 * @todo Description of function deleteChildren
	 * @param  $famId 
	 * @param  $famBatchId
	 * @return 
	 */
	public function delete_children( $fam_id, $fam_batch_id ) {
		$sql = 'DELETE FROM rp_fam_child WHERE fam_id = ?  AND fam_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpFamChildMySql
	 */
	public function load( $fam_id, $fam_batch_id, $child_id, $indi_batch_id ) {
		$sql = 'SELECT * FROM rp_fam_child WHERE fam_id = ?  AND fam_batch_id = ?  AND child_id = ?  AND indi_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set( $child_id );
		$sql_query->set_number( $indi_batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpFamChild primary key
	 */
	public function delete( $fam_id, $fam_batch_id, $child_id, $indi_batch_id ) {
		$sql = 'DELETE FROM rp_fam_child WHERE fam_id = ?  AND fam_batch_id = ?  AND child_id = ?  AND indi_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $fam_id );
		$sql_query->set_number( $fam_batch_id );
		$sql_query->set_number( $child_id );
		$sql_query->set( $indi_batch_id );
		return $this->execute_update( $sql_query );
	}
    
	/**
	 * Insert record to table
	 *
	 * @param RpFamChildMySql rpFamChild
	 */
	public function insert( $rp_fam_child ) {
		$sql = 'INSERT INTO rp_fam_child (update_datetime, fam_id, fam_batch_id, child_id, indi_batch_id) VALUES (now(), ?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_child->fam_id );
		$sql_query->set_number( $rp_fam_child->fam_batch_id );
		$sql_query->set( $rp_fam_child->child_id );
		$sql_query->set_number( $rp_fam_child->indi_batch_id );
		$this->execute_insert( $sql_query );
		//$rpFamChild->id = $id;
		//return $id;
		
	}
    
	/**
	 * Update record in table
	 *
	 * @param RpFamChildMySql rpFamChild
	 */
	public function update( $rp_fam_child ) {
		$sql = 'UPDATE rp_fam_child SET update_datetime = now() WHERE fam_id = ?  AND fam_batch_id = ?  AND child_id = ?  AND indi_batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam_child->fam_id );
		$sql_query->set_number( $rp_fam_child->fam_batch_id );
		$sql_query->set( $rp_fam_child->child_id );
		$sql_query->set_number( $rp_fam_child->indi_batch_id );
		return $this->execute_update( $sql_query );
	}

    /**
	 * Read row
	 *
	 * @return RpFamChildMySql
	 */
	protected function read_row( $row ) {
		$rp_fam_child = new RP_Fam_Child();
		$rp_fam_child->fam_id = $row['fam_id'];
		$rp_fam_child->fam_batch_id = $row['fam_batch_id'];
		$rp_fam_child->child_id = $row['child_id'];
		$rp_fam_child->indi_batch_id = $row['indi_batch_id'];
		$rp_fam_child->update_datetime = $row['update_datetime'];
		return $rp_fam_child;
	}
}
?>
