<?php
/**
 * class RP_that operate on table 'rp_name_name'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Name_Name_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpNameNameMySql
	 */
	public function load( $name_id, $assoc_name_id ) {
		$sql = 'SELECT * FROM rp_name_name WHERE name_id = ?  AND assoc_name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $name_id );
		$sql_query->set_number( $assoc_name_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpNameName primary key
	 */
	public function delete( $name_id, $assoc_name_id ) {
		$sql = 'DELETE FROM rp_name_name WHERE name_id = ?  AND assoc_name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $name_id );
		$sql_query->set_number( $assoc_name_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpNameNameMySql rpNameName
	 */
	public function insert( $rp_name_name ) {
		$sql = 'INSERT INTO rp_name_name (assoc_name_type, update_datetime, name_id, assoc_name_id) VALUES (?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_name->assoc_name_type );
		$sql_query->set_number( $rp_name_name->update_datetime );
		$sql_query->set_number( $rp_name_name->name_id );
		$sql_query->set_number( $rp_name_name->assoc_name_id );
		$this->execute_insert( $sql_query );
		//$rpNameName->id = $id;
		//return $id;
		
	}
	/**
	 * Update record in table
	 *
	 * @param RpNameNameMySql rpNameName
	 */
	public function update( $rp_name_name ) {
		$sql = 'UPDATE rp_name_name SET assoc_name_type = ?, update_datetime = ? WHERE name_id = ?  AND assoc_name_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_name->assoc_name_type );
		$sql_query->set_number( $rp_name_name->update_datetime );
		$sql_query->set_number( $rp_name_name->name_id );
		$sql_query->set_number( $rp_name_name->assoc_name_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpNameNameMySql
	 */
	protected function read_row( $row ) {
		$rp_name_name = new RP_Name_Name();
		$rp_name_name->name_id = $row['name_id'];
		$rp_name_name->assoc_name_id = $row['assoc_name_id'];
		$rp_name_name->assoc_name_type = $row['assoc_name_type'];
		$rp_name_name->update_datetime = $row['update_datetime'];
		return $rp_name_name;
	}
	
}
?>
