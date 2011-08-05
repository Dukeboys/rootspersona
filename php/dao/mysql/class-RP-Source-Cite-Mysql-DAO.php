<?php
/**
 * class RP_that operate on table 'rp_source_cite'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Source_Cite_Mysql_Dao extends Rp_Mysql_DAO {

	/**
	 * @todo Description of function deleteOrphans
	 * @param
	 * @return
	 */
	public function delete_orphans() {
		$sql = 'DELETE FROM rp_source_cite WHERE id NOT IN (SELECT cite_id FROM rp_event_cite)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->execute_update( $sql_query );
	}


	/**
	 * @todo Description of function deleteBySrc
	 * @param  $srcId
	 * @param  $srcBatchId
	 * @return
	 */
	public function delete_by_src( $src_id, $src_batch_id ) {
		$sql = 'DELETE FROM rp_source_cite WHERE source_id = ? and source_batch_id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $src_id );
		$sql_query->set_number( $src_batch_id );
		return $this->execute_update( $sql_query );
	}
    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpSourceCiteMySql
	 */
	public function load( $id ) {
		$sql = 'SELECT * FROM rp_source_cite WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpSourceCite primary key
	 */
	public function delete( $id ) {
		$sql = 'DELETE FROM rp_source_cite WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		return $this->execute_update( $sql_query );
	}
	/**
	 * Insert record to table
	 *
	 * @param RpSourceCiteMySql rpSourceCite
	 */
	public function insert( $rp_source_cite ) {
		$sql = 'INSERT INTO rp_source_cite (source_id, source_batch_id, source_page, event_type, event_role, quay, source_description, update_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, now())';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_source_cite->source_id );
		$sql_query->set_number( $rp_source_cite->source_batch_id );
		$sql_query->set( $rp_source_cite->source_page );
		$sql_query->set( $rp_source_cite->event_type );
		$sql_query->set( $rp_source_cite->event_role );
		$sql_query->set( $rp_source_cite->quay );
		$sql_query->set( $rp_source_cite->source_description );
		$id = $this->execute_insert( $sql_query );
		$rp_source_cite->id = $id;return $id;
	}
	/**
	 * Update record in table
	 *
	 * @param RpSourceCiteMySql rpSourceCite
	 */
	public function update( $rp_source_cite ) {
		$sql = 'UPDATE rp_source_cite SET source_id = ?, source_batch_id = ?, source_page = ?, event_type = ?, event_role = ?, quay = ?, source_description = ?, update_datetime = now() WHERE id = ?';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_source_cite->source_id );
		$sql_query->set_number( $rp_source_cite->source_batch_id );
		$sql_query->set( $rp_source_cite->source_page );
		$sql_query->set( $rp_source_cite->event_type );
		$sql_query->set( $rp_source_cite->event_role );
		$sql_query->set( $rp_source_cite->quay );
		$sql_query->set( $rp_source_cite->source_description );
		$sql_query->set_number( $rp_source_cite->id );
		return $this->execute_update( $sql_query );
	}

    public function query_by_src( $src_id, $src_batch_id ) {
		$sql = 'SELECT DISTINCT source_page, source_description'
            . ' FROM rp_source_cite'
            . ' WHERE source_id = ? and source_batch_id = ?'
            . ' ORDER BY source_page';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $src_id );
		$sql_query->set_number( $src_batch_id );
		$rows =  RP_Query_Executor::execute( $sql_query );
        $sources = null;
 		if ( $rows > 0 ) {
            $sources = array();
			$cnt = count( $rows );
			for ( $idx = 0;	$idx < $cnt;	$idx++ ) {
				$src = new RP_Source_Cite();
				$src->source_page = $rows[$idx]['source_page'];
                $src->source_description = $rows[$idx]['source_description'];
				$sources[$idx] = $src;
			}
		}
        return $sources;
	}
	/**
	 * Read row
	 *
	 * @return RpSourceCiteMySql
	 */
	protected function read_row( $row ) {
		$rp_source_cite = new RP_Source_Cite();
		$rp_source_cite->id = $row['id'];
		$rp_source_cite->source_id = $row['source_id'];
		$rp_source_cite->source_batch_id = $row['source_batch_id'];
		$rp_source_cite->source_page = $row['source_page'];
		$rp_source_cite->event_type = $row['event_type'];
		$rp_source_cite->event_role = $row['event_role'];
		$rp_source_cite->quay = $row['quay'];
		$rp_source_cite->source_description = $row['source_description'];
		$rp_source_cite->update_datetime = $row['update_datetime'];
		return $rp_source_cite;
	}

}
?>
