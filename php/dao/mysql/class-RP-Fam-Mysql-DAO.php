<?php
/**
 * class RP_that operate on table 'rp_fam'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-03 18:56
 */
class RP_Fam_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpFamMySql
	 */
	public function load( $id, $batch_id ) {
		$sql = 'SELECT * FROM rp_fam WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpFam primary key
	 */
	public function delete( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_fam WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Delete record FROM table
	 * @param rpFam primary key
	 */
	public function delete_spouse( $id, $batch_id ) {
		$sql = 'UPDATE rp_fam SET spouse1=null,indi_batch_id_1=null WHERE spouse1 = ?  AND indi_batch_id_1 = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		$this->execute_update( $sql_query );

		$sql = 'UPDATE rp_fam SET spouse2=null,indi_batch_id_2=null WHERE spouse2 = ?  AND indi_batch_id_2 = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		$this->execute_update( $sql_query );

		$sql = 'DELETE FROM rp_fam WHERE spouse1 IS NULL AND spouse2 IS NULL';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$this->execute_update( $sql_query );
		return $this->execute_update( $sql_query );
	}
	public function get_next_id(  ) {
		$sql = 'UPDATE rp_fam_seq SET id=LAST_INSERT_ID(id+1)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $this->execute_update($sql_query);
		$sql = 'SELECT LAST_INSERT_ID()';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->query_single_result( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpFamMySql rpFam
	 */
	public function insert( $rp_fam ) {
        if($rp_fam->id == null || empty($rp_fam->id)) {
            $rp_fam->id = $this->get_next_id();
        }
		$sql = 'INSERT INTO rp_fam (restriction_notice, spouse1, indi_batch_id_1, spouse2, indi_batch_id_2, auto_rec_id, ged_change_date, update_datetime, id, batch_id) VALUES (?, ?, ?, ?, ?, ?, ?, now(), ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam->restriction_notice );
		$sql_query->set( $rp_fam->spouse1 );
		$sql_query->set_number( $rp_fam->indi_batch_id1 );
		$sql_query->set( $rp_fam->spouse2 );
		$sql_query->set_number( $rp_fam->indi_batch_id2 );
		$sql_query->set( $rp_fam->auto_rec_id );
		$sql_query->set( $rp_fam->ged_change_date );
		$sql_query->set( $rp_fam->id );
		$sql_query->set_number( $rp_fam->batch_id );
		$this->execute_insert( $sql_query );
		return $rp_fam->id;

	}
	/**
	 * Update record in table
	 *
	 * @param RpFamMySql rpFam
	 */
	public function update( $rp_fam ) {
		$sql = 'UPDATE rp_fam SET restriction_notice = ?, spouse1 = ?, indi_batch_id_1 = ?, spouse2 = ?, indi_batch_id_2 = ?, auto_rec_id = ?, ged_change_date = ?, update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_fam->restriction_notice );
		$sql_query->set( $rp_fam->spouse1 );
		$sql_query->set_number( $rp_fam->indi_batch_id1 );
		$sql_query->set( $rp_fam->spouse2 );
		$sql_query->set_number( $rp_fam->indi_batch_id2 );
		$sql_query->set( $rp_fam->auto_rec_id );
		$sql_query->set( $rp_fam->ged_change_date );
		$sql_query->set( $rp_fam->id );
		$sql_query->set_number( $rp_fam->batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpFamMySql
	 */
	protected function read_row( $row ) {
		$rp_fam = new RP_Fam();
		$rp_fam->id = $row['id'];
		$rp_fam->batch_id = $row['batch_id'];
		$rp_fam->restriction_notice = $row['restriction_notice'];
		$rp_fam->spouse1 = $row['spouse1'];
		$rp_fam->indi_batch_id1 = $row['indi_batch_id_1'];
		$rp_fam->spouse2 = $row['spouse2'];
		$rp_fam->indi_batch_id2 = $row['indi_batch_id_2'];
		$rp_fam->auto_rec_id = $row['auto_rec_id'];
		$rp_fam->ged_change_date = $row['ged_change_date'];
		$rp_fam->update_datetime = $row['update_datetime'];
		return $rp_fam;
	}
}
?>
