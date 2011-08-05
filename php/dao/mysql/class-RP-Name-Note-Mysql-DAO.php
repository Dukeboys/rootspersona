<?php
/**
 * class RP_that operate on table 'rp_name_note'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-07 19:07
 */
class RP_Name_Note_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpNameNoteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_name_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpNameNote primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_name_note WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpNameNoteMySql rpNameNote
	 */
	public function insert( $rp_name_note ) {
		$sql = 'INSERT INTO rp_name_note (name_id, note, update_datetime) VALUES (?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_name_note->name_id );
		$sql_query->set( $rp_name_note->note );
		$sql_query->set_number( $rp_name_note->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_name_note->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpNameNoteMySql rpNameNote
	 */
	public function update( $rp_name_note ) {
		$sql = 'UPDATE rp_name_note SET name_id = ?, note = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $rp_name_note->name_id );
		$sql_query->set( $rp_name_note->note );
		$sql_query->set_number( $rp_name_note->update_datetime );
		$sql_query->set_number( $rp_name_note->id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpNameNoteMySql
	 */
	protected function read_row( $row ) {
		$rp_name_note = new RP_Name_Note();
		$rp_name_note->id = $row['id'];
		$rp_name_note->name_id = $row['name_id'];
		$rp_name_note->note = $row['note'];
		$rp_name_note->update_datetime = $row['update_datetime'];
		return $rp_name_note;
	}

}
?>
