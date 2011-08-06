<?php
/**
 * class RP_that operate on table 'rp_name_personal'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Name_Personal_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpNamePersonalMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_name_personal WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpNamePersonal primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_name_personal WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpNamePersonalMySql rpNamePersonal
	 */
	public function insert( $rp_name_personal ) {
		$sql = 'INSERT INTO rp_name_personal (personal_name, name_type, prefix, given, nickname, surname_prefix, surname, suffix, update_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_personal->personal_name );
		$sql_query->set( $rp_name_personal->name_type );
		$sql_query->set( $rp_name_personal->prefix );
		$sql_query->set( $rp_name_personal->given );
		$sql_query->set( $rp_name_personal->nickname );
		$sql_query->set( $rp_name_personal->surname_prefix );
		$sql_query->set( $rp_name_personal->surname );
		$sql_query->set( $rp_name_personal->suffix );
		$sql_query->set( $rp_name_personal->update_datetime );
		$id = $this->execute_insert( $sql_query );
		$rp_name_personal->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpNamePersonalMySql rpNamePersonal
	 */
	public function update( $rp_name_personal ) {
		$sql = 'UPDATE rp_name_personal SET personal_name = ?, name_type = ?, prefix = ?, given = ?, nickname = ?, surname_prefix = ?, surname = ?, suffix = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_name_personal->personal_name );
		$sql_query->set( $rp_name_personal->name_type );
		$sql_query->set( $rp_name_personal->prefix );
		$sql_query->set( $rp_name_personal->given );
		$sql_query->set( $rp_name_personal->nickname );
		$sql_query->set( $rp_name_personal->surname_prefix );
		$sql_query->set( $rp_name_personal->surname );
		$sql_query->set( $rp_name_personal->suffix );
		$sql_query->set( $rp_name_personal->update_datetime );
		$sql_query->set_number( $rp_name_personal->id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Read row
	 *
	 * @return RpNamePersonalMySql
	 */
	protected function read_row( $row ) {
		$rp_name_personal = new RP_Name_Personal();
		$rp_name_personal->id = $row['id'];
		$rp_name_personal->personal_name = $row['personal_name'];
		$rp_name_personal->name_type = $row['name_type'];
		$rp_name_personal->prefix = $row['prefix'];
		$rp_name_personal->given = $row['given'];
		$rp_name_personal->nickname = $row['nickname'];
		$rp_name_personal->surname_prefix = $row['surname_prefix'];
		$rp_name_personal->surname = $row['surname'];
		$rp_name_personal->suffix = $row['suffix'];
		$rp_name_personal->update_datetime = $row['update_datetime'];
		return $rp_name_personal;
	}

}
?>
