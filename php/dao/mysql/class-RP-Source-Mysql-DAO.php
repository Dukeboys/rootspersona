<?php
/**
 * class RP_that operate on table 'rp_source'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2011-04-02 09:44
 */
class RP_Source_Mysql_Dao extends Rp_Mysql_DAO {

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return RpSourceMySql
	 */
	public function load( $id, $batch_id ) {
		$sql = 'SELECT * FROM rp_source WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $id );
		$sql_query->set_number( $batch_id );
		return $this->get_row( $sql_query );
	}

	/**
	 * Delete record FROM table
	 * @param rpSource primary key
	 */
	public function delete( $id, $batch_id ) {
		$sql = 'DELETE FROM rp_source WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * Insert record to table
	 *
	 * @param RpSourceMySql rpSource
	 */
	public function insert( $rp_source ) {
		$sql = 'INSERT INTO rp_source (originator, source_title,'
            . 'abbr, publication_facts, text, auto_rec_id, ged_change_date,'
            . 'update_datetime, wp_page_id, id, batch_id)'
            . ' VALUES (?, ?, ?, ?, ?, ?, ?, now(), ?, ?, ?)';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_source->originator );
		$sql_query->set( $rp_source->source_title );
		$sql_query->set( $rp_source->abbr );
		$sql_query->set( $rp_source->publication_facts );
		$sql_query->set( $rp_source->text );
		$sql_query->set( $rp_source->auto_rec_id );
		$sql_query->set( $rp_source->ged_change_date );
		$sql_query->set_number( $rp_source->wp_page_id );
		$sql_query->set( $rp_source->id );
		$sql_query->set_number( $rp_source->batch_id );
		$this->execute_insert( $sql_query );
		//$rpSource->id = $id;
		//return $id;

	}

	/**
	 * Update record in table
	 *
	 * @param RpSourceMySql rpSource
	 */
	public function update( $rp_source ) {
		$sql = 'UPDATE rp_source SET originator = ?, source_title = ?,'
            . 'abbr = ?, publication_facts = ?, text = ?, auto_rec_id = ?,'
            . 'ged_change_date = ?, wp_page_id = ?, update_datetime = now()'
            . ' WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $rp_source->originator );
		$sql_query->set( $rp_source->source_title );
		$sql_query->set( $rp_source->abbr );
		$sql_query->set( $rp_source->publication_facts );
		$sql_query->set( $rp_source->text );
		$sql_query->set( $rp_source->auto_rec_id );
		$sql_query->set( $rp_source->ged_change_date );
		$sql_query->set_number( $rp_source->wp_page_id );
		$sql_query->set( $rp_source->id );
		$sql_query->set_number( $rp_source->batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * @todo Description of function updatePage
	 * @param  $id
	 * @param  $batchId
	 * @param  $pageId
	 * @return
	 */
	public function update_page( $id, $batch_id, $page_id ) {
		$sql = 'UPDATE rp_source SET wp_page_id = ?,'
            . 'update_datetime = now() WHERE id = ?  AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $page_id );
		$sql_query->set( $id );
		$sql_query->set( $batch_id );
		return $this->execute_update( $sql_query );
	}

	/**
	 * @todo Description of function unlinkAllPages
	 * @param
	 * @return
	 */
	public function unlink_all_pages() {
		$sql = 'UPDATE rp_source SET wp_page_id = null, update_datetime = now()';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		return $this->execute_update( $sql_query );
	}

	/**
	 * @todo Description of function getPageId
	 * @param  $id
	 * @param  $batchId
	 * @return
	 */
	public function get_page_id( $id, $batch_id ) {
		$sql = 'SELECT wp_page_id FROM rp_source WHERE id = ? AND batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		return $this->query_single_result( $sql_query );
	}

	/**
	 * @todo Description of function getSourceNoPage
	 * @param  $batchId
	 * @return
	 */
	public function get_source_no_page( $batch_id ) {
		$sql = "SELECT rs.id AS id, rs.abbr AS title"
                . " FROM rp_source rs"
                . " WHERE rs.wp_page_id IS null AND rs.batch_id = ?";
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $batch_id );
		$rows = RP_Query_Executor::execute( $sql_query );
		$sources = array();
		if ( $rows > 0 ) {
			$cnt = count( $rows );
			for ( $idx = 0;	$idx < $cnt;	$idx++ ) {
				$src = array();
				$src['id'] = $rows[$idx]['id'];
                $src['title'] = $rows[$idx]['title'];
				$sources[$idx] = $src;
			}
		}
		return $sources;
	}

    public function get_source($id, $batch_id, $options) {
		$sql = 'SELECT rs.id AS id,rs.source_title AS title'
            . ' FROM rp_source rs WHERE rs.id = ? AND rs.batch_id = ? ';
		$sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set( $id );
		$sql_query->set_number( $batch_id );
		$row =  RP_Query_Executor::execute( $sql_query );
        $src = new RP_Evidence();
        $src->id = $row[0]['id'];
        $src->batch_id = $batch_id;
        $src->source_title = $row[0]['title'];
        $src->citations = RP_Dao_Factory::get_rp_source_cite_dao($this->prefix)
                ->query_by_src($src->id, $batch_id);
        $src->persons = $this->get_persons( $src, $options );
        $src->notes = RP_Dao_Factory::get_rp_source_note_dao($this->prefix)
                ->query_by_src($src->id, $batch_id);
        return $src;
    }

    private function get_persons( $src, $options ) {
        $sql =  'SELECT DISTINCT ri.id AS id'
                . ',rnp.surname AS surname'
                . ',rnp.given AS given'
                . ',ri.wp_page_id AS page'
                . ',rio.privacy_code AS privacy_code'
                . ' FROM rp_indi ri'
                . ' LEFT OUTER JOIN rp_indi_option rio'
                . ' ON rio.indi_batch_id = ri.batch_id AND rio.indi_id = ri.id'
                . ' JOIN rp_indi_name rip'
                . ' ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id'
                . ' JOIN rp_name_personal rnp ON rip.name_id = rnp.id'
                . " WHERE ri.batch_id = ? AND ri.wp_page_id IS NOT null"
                . " AND IFNULL(rio.privacy_code,'Def') != '"
                . RP_Persona_Helper::EXC
                . "' AND ri.id IN "
                . '(SELECT ric.indi_id AS id'
                . ' FROM rp_indi_cite ric'
                . ' JOIN rp_source_cite rsc ON ric.cite_id = rsc.id'
                . ' WHERE rsc.source_id = ? AND rsc.source_batch_id = ?'
                . ' UNION'
                . ' SELECT rie.indi_id AS id'
                . ' FROM rp_indi_event rie'
                . ' JOIN rp_event_cite rec ON rie.event_id = rec.event_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rec.cite_id'
                . ' WHERE rsc.source_id = ? AND rsc.source_batch_id = ?'
                . ' UNION'
                . ' SELECT rif.indi_id AS id'
                . ' FROM rp_indi_fam rif'
                . ' JOIN rp_fam_cite rfc ON rfc.fam_id = rif.fam_id AND rfc.fam_batch_id = rif.fam_batch_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rfc.cite_id'
                . ' WHERE rsc.source_id = ? AND rsc.source_batch_id = ?'
                . ' UNION'
                . ' SELECT rin.indi_id AS id'
                . ' FROM rp_indi_name rin'
                . ' JOIN rp_name_cite rnc ON rnc.name_id = rin.name_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rnc.cite_id'
                . ' WHERE rsc.source_id = ? AND rsc.source_batch_id = ?)'
                . ' ORDER BY rnp.surname, rnp.given';

        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
		$sql_query->set_number( $src->batch_id );
        $sql_query->set( $src->id );
		$sql_query->set_number( $src->batch_id );
        $sql_query->set( $src->id );
		$sql_query->set_number( $src->batch_id );
        $sql_query->set( $src->id );
		$sql_query->set_number( $src->batch_id );
        $sql_query->set( $src->id );
		$sql_query->set_number( $src->batch_id );
        $rows =  RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $persons = null;
        if ( $cnt > 0 ) {
            $persons = array();
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $persona = new RP_Persona();
                $persona->surname = $rows[$idx]['surname'];
                $persona->given = $rows[$idx]['given'];
                $persona->page = $rows[$idx]['page'];
                $persona->privacy = $rows[$idx]['privacy_code'];
                $persona->birth_date = RP_Dao_Factory::get_rp_persona_dao( $this->prefix )
                        ->get_birth_date( $src->batch_id, $rows[$idx]['id'] );
                $persona->death_date = RP_Dao_Factory::get_rp_persona_dao( $this->prefix )
                        ->get_death_date( $src->batch_id, $rows[$idx]['id'] );
                $pscore = RP_Persona_Helper::score_persona($persona, $options);
                if( ! RP_Persona_Helper::is_restricted($options['uscore'], $pscore ) ) {
                    $persons[$idx] = $persona;
                }
            }
        }
        return $persons;
    }

    public function get_sources_with_pages() {
        $sql = "SELECT rs.id AS id, rs.batch_id AS batch_id"
            . ",rs.abbr AS abbr"
            . ",rs.wp_page_id AS page"
            . " FROM rp_source rs"
            . " WHERE rs.wp_page_id IS NOT NULL";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $rows = RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $sources = null;
        if ( $cnt > 0 ) {
            $sources = array();
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $s = array();
                $s['id'] = $rows[$idx]['id'];
                $s['batch_id'] = $rows[$idx]['batch_id'];
                $s['title'] = $rows[$idx]['abbr'];
                $s['page_id'] = $rows[$idx]['page'];
                $sources[$idx] = $s;
            }
        }
        return $sources;
    }

	/**
	 * Read row
	 *
	 * @return RpSourceMySql
	 */
	protected function read_row( $row ) {
		$rp_source = new RP_Source();
		$rp_source->id = $row['id'];
		$rp_source->batch_id = $row['batch_id'];
		$rp_source->originator = $row['originator'];
		$rp_source->source_title = $row['source_title'];
		$rp_source->abbr = $row['abbr'];
		$rp_source->publication_facts = $row['publication_facts'];
		$rp_source->text = $row['text'];
		$rp_source->auto_rec_id = $row['auto_rec_id'];
		$rp_source->ged_change_date = $row['ged_change_date'];
		$rp_source->update_datetime = $row['update_datetime'];
		return $rp_source;
	}
}
?>