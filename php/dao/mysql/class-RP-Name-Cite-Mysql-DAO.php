<?php
/**
 * class RP_that operate on table 'rp_name_cite'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Name_Cite_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpNameCiteMySql
	 */
	public function load( $name_id, $cite_id ) {
		$sql = 'SELECT * FROM rp_name_cite WHERE name_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $name_id );
		$sql_query->set_number( $cite_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpNameCite primary key
	 */
	public function delete( $name_id, $cite_id ) {
		$sql = 'DELETE FROM rp_name_cite WHERE name_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $name_id );
		$sql_query->set_number( $cite_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpNameCiteMySql rpNameCite
	 */
	public function insert( $rp_name_cite ) {
		$sql = 'INSERT INTO rp_name_cite (update_datetime, name_id, cite_id) VALUES (?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_cite->update_datetime );
		$sql_query->set_number( $rp_name_cite->name_id );
		$sql_query->set_number( $rp_name_cite->cite_id );
		$this->execute_insert( $sql_query );
		//$rpNameCite->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpNameCiteMySql rpNameCite
	 */
	public function update( $rp_name_cite ) {
		$sql = 'UPDATE rp_name_cite SET update_datetime = ? WHERE name_id = ?  AND cite_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_cite->update_datetime );
		$sql_query->set_number( $rp_name_cite->name_id );
		$sql_query->set_number( $rp_name_cite->cite_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpNameCiteMySql
	 */
	protected function read_row( $row ) {
		$rp_name_cite = new RP_Name_Cite();
		$rp_name_cite->name_id = $row['name_id'];
		$rp_name_cite->cite_id = $row['cite_id'];
		$rp_name_cite->update_datetime = $row['update_datetime'];
		return $rp_name_cite;
	}

}
?>
