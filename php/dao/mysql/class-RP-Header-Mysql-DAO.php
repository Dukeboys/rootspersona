<?php
/**
 * class RP_that operate on table 'rp_header'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Header_Mysql_Dao extends Rp_Mysql_DAO {
	/**
	 * @todo Description of function deleteByBatchId
	 * @param  $batchId
	 * @return 
	 */
	public function delete_by_batch_id( $batch_id ) {
		$sql = 'DELETE FROM rp_header WHERE batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpHeaderMySql
	 */
	public function load( $id, $batch_id ) {
		$sql = 'SELECT * FROM rp_header WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpHeader primary key
	 */
	public function delete( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_header WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpHeaderMySql rpHeader
	 */
	public function insert( $rp_header ) {
		$sql = 'INSERT INTO rp_header (src_system_id, src_system_version, product_name, corp_name, corp_addr_id, src_data_name, publication_date, copyright_src_data, receiving_sys_name, transmission_date, transmission_time, submitter_id, submitter_batch_id, submission_id, submission_batch_id, file_name, copyright_ged_file, lang, gedc_version, gedc_form, char_set, char_set_version, place_hierarchy, ged_content_description, update_datetime, batch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query = $this->set( $sql_query, $rp_header );
		$sql_query->set_number( $rp_header->batch_id );
		$this->execute_insert( $sql_query );
	}
	/**
	 * Update record in table
	 *
	 * @param RpHeaderMySql rpHeader
	 */
	public function update( $rp_header ) {
		$sql = 'UPDATE rp_header SET src_system_id = ?, src_system_version = ?, product_name = ?, corp_name = ?, corp_addr_id = ?, src_data_name = ?, publication_date = ?, copyright_src_data = ?, receiving_sys_name = ?, transmission_date = ?, transmission_time = ?, submitter_id = ?, submitter_batch_id = ?, submission_id = ?, submission_batch_id = ?, file_name = ?, copyright_ged_file = ?, lang = ?, gedc_version = ?, gedc_form = ?, char_set = ?, char_set_version = ?, place_hierarchy = ?, ged_content_description = ?, update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query = $this->set( $sql_query, $rp_header );
		$sql_query->set_number( $rp_header->id );
		$sql_query->set_number( $rp_header->batch_id );
		return $this->execute_update( $sql_query );
	}
	

	/**
	 * @todo Description of function set
	 * @param  $sqlQuery 
	 * @param  $rpHeader
	 * @return 
	 */
	private function set( $sql_query, $rp_header ) {
		$sql_query->set( $rp_header->src_system_id );
		$sql_query->set( $rp_header->src_system_version );
		$sql_query->set( $rp_header->product_name );
		$sql_query->set( $rp_header->corp_name );
		$sql_query->set_number( $rp_header->corp_addr_id );
		$sql_query->set( $rp_header->src_data_name );
		$sql_query->set( $rp_header->publication_date );
		$sql_query->set( $rp_header->copyright_src_data );
		$sql_query->set( $rp_header->receiving_sys_name );
		$sql_query->set( $rp_header->transmission_date );
		$sql_query->set( $rp_header->transmission_time );
		$sql_query->set( $rp_header->submitter_id );
		$sql_query->set_number( $rp_header->submitter_batch_id );
		$sql_query->set( $rp_header->submission_id );
		$sql_query->set_number( $rp_header->submission_batch_id );
		$sql_query->set( $rp_header->file_name );
		$sql_query->set( $rp_header->copyright_ged_file );
		$sql_query->set( $rp_header->lang );
		$sql_query->set( $rp_header->gedc_version );
		$sql_query->set( $rp_header->gedc_form );
		$sql_query->set( $rp_header->char_set );
		$sql_query->set( $rp_header->char_set_version );
		$sql_query->set( $rp_header->place_hierarchy );
		$sql_query->set( $rp_header->ged_content_description );
		return $sql_query;
	}
	/**
	 * Read row
	 *
	 * @return RpHeaderMySql
	 */
	protected function read_row( $row ) {
		$rp_header = new RP_Header();
		$rp_header->id = $row['id'];
		$rp_header->batch_id = $row['batch_id'];
		$rp_header->src_system_id = $row['src_system_id'];
		$rp_header->src_system_version = $row['src_system_version'];
		$rp_header->product_name = $row['product_name'];
		$rp_header->corp_name = $row['corp_name'];
		$rp_header->corp_addr_id = $row['corp_addr_id'];
		$rp_header->src_data_name = $row['src_data_name'];
		$rp_header->publication_date = $row['publication_date'];
		$rp_header->copyright_src_data = $row['copyright_src_data'];
		$rp_header->receiving_sys_name = $row['receiving_sys_name'];
		$rp_header->transmission_date = $row['transmission_date'];
		$rp_header->transmission_time = $row['transmission_time'];
		$rp_header->submitter_id = $row['submitter_id'];
		$rp_header->submitter_batch_id = $row['submitter_batch_id'];
		$rp_header->submission_id = $row['submission_id'];
		$rp_header->submission_batch_id = $row['submission_batch_id'];
		$rp_header->file_name = $row['file_name'];
		$rp_header->copyright_ged_file = $row['copyright_ged_file'];
		$rp_header->lang = $row['lang'];
		$rp_header->gedc_version = $row['gedc_version'];
		$rp_header->gedc_form = $row['gedc_form'];
		$rp_header->char_set = $row['char_set'];
		$rp_header->char_set_version = $row['char_set_version'];
		$rp_header->place_hierarchy = $row['place_hierarchy'];
		$rp_header->ged_content_description = $row['ged_content_description'];
		$rp_header->update_datetime = $row['update_datetime'];
		return $rp_header;
	}
}
?>
