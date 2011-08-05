<?php
/**
 * @todo Description of class RP_RpMySqlDAO
 * @author ed4becky
 * @copyright 2010-2011  Ed Thompson  (email : ed@ed4becky.org)
 * @version 2.0.x
 * @package rootspersona_php
 * @subpackage 
 * @category rootsPersona
 * @link www.ed4becky.net
 * @since 2.0.0
 * @license http://www.opensource.org/licenses/GPL-2.0
 */
class RP_Mysql_DAO {
	var $prefix;

	function __construct( $prefix ) {
		$this->prefix = $prefix;
	}
    
 	public function load(  $table_name, $id_col, $batch_col, $id, $batch_id  ) {
		$sql = "SELECT * FROM $table_name WHERE $id_col = $id AND $batch_col = $batch_id";
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->get_row( $sql_query );
	}

	public function query_all($table_name) {
		$sql = 'SELECT * FROM ' . $table_name;
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->get_list( $sql_query );
	}

	public function query_all_order_by( $table_name, $order_column ) {
		$sql = 'SELECT * FROM '. $table_name . ' ORDER BY ' . $order_column;
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->get_list( $sql_query );
	}

	public function delete( $table_name, $id_col, $batch_col, $id, $batch_id ) {
		$sql = "DELETE FROM $table_name WHERE $id_col = $id AND $batch_col = $batch_id";
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}   
    
    protected function get_row( $sql_query ) {
		$tab = RP_Query_Executor::execute( $sql_query );
		if ( count( $tab ) == 0 ) {
			return null;
		}
		return $this->read_row( $tab[0] );
	}
    
    protected function get_list( $sql_query ) {
		$tab = RP_Query_Executor::execute( $sql_query );
		$ret = array();
		for ( $i = 0; $i < count( $tab ); $i++ ) {
			$ret[$i] = $this->read_row( $tab[$i] );
		}
		return $ret;
	}
    
    public function clean( $table_name ) {
		$sql = 'DELETE FROM ' . $table_name;
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->execute_update( $sql_query );
	}

	protected function execute( $sql_query ) {
		return RP_Query_Executor::execute( $sql_query );
	}

	protected function execute_update( $sql_query ) {
		return RP_Query_Executor::execute_update( $sql_query );
	}

	protected function query_single_result( $sql_query ) {
		return RP_Query_Executor::query_for_string( $sql_query );
	}

	protected function execute_insert( $sql_query ) {
		return RP_Query_Executor::execute_insert( $sql_query );
	}
}
