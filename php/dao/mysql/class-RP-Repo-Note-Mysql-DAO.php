<?php
/**
 * class RP_that operate on table 'rp_repo_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Repo_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpRepoNoteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_repo_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpRepoNote primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_repo_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpRepoNoteMySql rpRepoNote
	 */
	public function insert( $rp_repo_note ) {
		$sql = 'INSERT INTO rp_repo_note (repo_id, repo_batch_id, note, update_datetime) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_repo_note->repo_id );
		$sql_query->set_number( $rp_repo_note->repo_batch_id );
		$sql_query->set( $rp_repo_note->note );
		$sql_query->set( $rp_repo_note->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_repo_note->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpRepoNoteMySql rpRepoNote
	 */
	public function update( $rp_repo_note ) {
		$sql = 'UPDATE rp_repo_note SET repo_id = ?, repo_batch_id = ?, note = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_repo_note->repo_id );
		$sql_query->set_number( $rp_repo_note->repo_batch_id );
		$sql_query->set( $rp_repo_note->note );
		$sql_query->set( $rp_repo_note->update_datetime );
		$sql_query->set_number( $rp_repo_note->id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpRepoNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_repo_note = new RP_Repo_Note();
		$rp_repo_note->id = $row['id'];
		$rp_repo_note->repo_id = $row['repo_id'];
		$rp_repo_note->repo_batch_id = $row['repo_batch_id'];
		$rp_repo_note->note = $row['note'];
		$rp_repo_note->update_datetime = $row['update_datetime'];
		return $rp_repo_note;
	}
	
}
?>
