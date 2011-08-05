<?php
/**
 * class RP_that operate on table 'rp_address'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Address_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpAddressMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_address WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

    /**
	 * Delete record FROM table
	 * @param rpAddres primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_address WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpAddressMySql rpAddres
	 */
	public function insert( $rp_addres ) {
		$sql = 'INSERT INTO rp_address (line1, line2, line3, city, ctry_subentity, ctry, postal_code, phone1, phone2, phone3, email1, email2, email3, www1, www2, www3, fax1, fax2, fax3, update_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query = $this->set( $sql_query, $rp_addres );
		$id = $this->execute_insert( $sql_query );
		$rp_addres->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpAddressMySql rpAddres
	 */
	public function update( $rp_addres ) {
		$sql = 'UPDATE rp_address SET line1 = ?, line2 = ?, line3 = ?, city = ?, ctry_subentity = ?, ctry = ?, postal_code = ?, phone1 = ?, phone2 = ?, phone3 = ?, email1 = ?, email2 = ?, email3 = ?, www1 = ?, www2 = ?, www3 = ?, fax1 = ?, fax2 = ?, fax3 = ?, update_datetime = ? WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query = $this->set( $sql_query, $rp_addres );
		$sql_query->set_number( $rp_addres->id );
		return $this->execute_update( $sql_query );
	}
	

	/**
	 * @todo Description of function set
	 * @param  $sqlQuery 
	 * @param  $rpAddres
	 * @return 
	 */
	private function set( $sql_query, $rp_addres ) {
		$sql_query->set( $rp_addres->line1 );
		$sql_query->set( $rp_addres->line2 );
		$sql_query->set( $rp_addres->line3 );
		$sql_query->set( $rp_addres->city );
		$sql_query->set( $rp_addres->ctry_subentity );
		$sql_query->set( $rp_addres->ctry );
		$sql_query->set( $rp_addres->postal_code );
		$sql_query->set( $rp_addres->phone1 );
		$sql_query->set( $rp_addres->phone2 );
		$sql_query->set( $rp_addres->phone3 );
		$sql_query->set( $rp_addres->email1 );
		$sql_query->set( $rp_addres->email2 );
		$sql_query->set( $rp_addres->email3 );
		$sql_query->set( $rp_addres->www1 );
		$sql_query->set( $rp_addres->www2 );
		$sql_query->set( $rp_addres->www3 );
		$sql_query->set( $rp_addres->fax1 );
		$sql_query->set( $rp_addres->fax2 );
		$sql_query->set( $rp_addres->fax3 );
		$sql_query->set( $rp_addres->update_datetime );
		return $sql_query;
	}
	/**
	 * Read row
	 *
	 * @return RpAddressMySql
	 */
	protected function read_row( $row ) {
		$rp_addres = new RP_Addres();
		$rp_addres->id = $row['id'];
		$rp_addres->line1 = $row['line1'];
		$rp_addres->line2 = $row['line2'];
		$rp_addres->line3 = $row['line3'];
		$rp_addres->city = $row['city'];
		$rp_addres->ctry_subentity = $row['ctry_subentity'];
		$rp_addres->ctry = $row['ctry'];
		$rp_addres->postal_code = $row['postal_code'];
		$rp_addres->phone1 = $row['phone1'];
		$rp_addres->phone2 = $row['phone2'];
		$rp_addres->phone3 = $row['phone3'];
		$rp_addres->email1 = $row['email1'];
		$rp_addres->email2 = $row['email2'];
		$rp_addres->email3 = $row['email3'];
		$rp_addres->www1 = $row['www1'];
		$rp_addres->www2 = $row['www2'];
		$rp_addres->www3 = $row['www3'];
		$rp_addres->fax1 = $row['fax1'];
		$rp_addres->fax2 = $row['fax2'];
		$rp_addres->fax3 = $row['fax3'];
		$rp_addres->update_datetime = $row['update_datetime'];
		return $rp_addres;
	}
	

}
?>
