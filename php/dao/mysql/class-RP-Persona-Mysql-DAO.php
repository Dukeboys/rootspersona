<?php


/**
 * @todo Description of class RP_RpPersonaMySqlDao
 * @author ed4becky
 * @copyright 2010-2011  Ed Thompson  (email : ed@ed4becky.org)
 * @version 2.0.x
 * @package rootspersona_php
 * @subpackage
 * @category rootspersona
 * @link www.ed4becky.net
 * @since 2.0.0
 * @license http://www.opensource.org/licenses/GPL-2.0
 */
class RP_Persona_Mysql_Dao extends Rp_Mysql_DAO {

    private static $_pcache = array();
    private static $_fcache = array();

    public function get_family_list( $term, $options ) {
        $sql = " SELECT concat('I','-',ri.id) AS id,"
            . " concat(rnp.surname,',',rnp.given) AS name"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?"
            . " UNION"
            . " SELECT concat('F','-',rf.id) AS id,"
            . " concat(rnp.surname,',',rnp.given,' & ',rnp2.surname,',',rnp2.given) AS name"
            . " FROM rp_fam rf"
            . " JOIN rp_indi_name rip"
            . " ON rf.spouse1 = rip.indi_id"
            . " AND rf.indi_batch_id_1 = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " JOIN rp_indi_name rip2"
            . " ON rf.spouse2 = rip2.indi_id"
            . " AND rf.indi_batch_id_2 = rip2.indi_batch_id"
            . " JOIN rp_name_personal rnp2"
            . " ON rip2.name_id = rnp2.id"
            . " WHERE rf.indi_batch_id_1 = '1'"
            . " AND rf.spouse1 IN (SELECT ri.id AS id"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?)"
            . " UNION"
            . " SELECT concat('F','-',rf.id) AS id,"
            . " concat(rnp.surname,',',rnp.given,' & ',rnp2.surname,',',rnp2.given) AS name"
            . " FROM rp_fam rf"
            . " JOIN rp_indi_name rip"
            . " ON rf.spouse2 = rip.indi_id"
            . " AND rf.indi_batch_id_2 = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " JOIN rp_indi_name rip2"
            . " ON rf.spouse1 = rip2.indi_id"
            . " AND rf.indi_batch_id_1 = rip2.indi_batch_id"
            . " JOIN rp_name_personal rnp2"
            . " ON rip2.name_id = rnp2.id"
            . " WHERE rf.indi_batch_id_2 = '1'"
            . " AND rf.spouse2 IN (SELECT ri.id AS id"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?)"
            . " ORDER BY name";


        $sql_query = new RP_Sql_Query($sql, $this->prefix );
        $sql_query->set($term . '%');
        $sql_query->set($term . '%');
        $sql_query->set($term . '%');
		$tab = RP_Query_Executor::execute($sql_query);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
            $ret[$i] = array();
            $ret[$i]['name'] =  '(' . $tab[$i]['id'] . ') '
                            . $tab[$i]['name'];
		}
		return $ret;
    }

    public function get_spouses_list( $term, $options ) {
        $sql = " SELECT concat('I','-',ri.id) AS id,"
            . " concat(rnp.surname,',',rnp.given) AS name"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?"
            . " UNION"
            . " SELECT concat('F1','-',rf.id) AS id,"
            . " concat(rnp.surname,',',rnp.given, '& Family [', rf.spouse1, ']') AS name"
            . " FROM rp_fam rf"
            . " JOIN rp_indi_name rip"
            . " ON rf.spouse1 = rip.indi_id"
            . " AND rf.indi_batch_id_1 = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE rf.indi_batch_id_1 = '1'"
            . " AND rf.spouse2 IS NULL"
            . " AND rf.spouse1 IN (SELECT ri.id AS id"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?)"
            . " UNION"
            . " SELECT concat('F2','-',rf.id) AS id,"
            . " concat(rnp.surname,',',rnp.given, '& Family [', rf.spouse2, ']') AS name"
            . " FROM rp_fam rf"
            . " JOIN rp_indi_name rip"
            . " ON rf.spouse2 = rip.indi_id"
            . " AND rf.indi_batch_id_2 = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE rf.indi_batch_id_2 = '1'"
            . " AND rf.spouse1 IS NULL"
            . " AND rf.spouse2 IN (SELECT ri.id AS id"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip"
            . " ON ri.id = rip.indi_id"
            . " AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp"
            . " ON rip.name_id = rnp.id"
            . " WHERE ri.batch_id = '1'"
            . " AND rip.seq_nbr = 1"
            . " AND upper(rnp.surname) LIKE ?)"
            . " ORDER BY name";


        $sql_query = new RP_Sql_Query($sql, $this->prefix );
        $sql_query->set($term . '%');
        $sql_query->set($term . '%');
        $sql_query->set($term . '%');
		$tab = RP_Query_Executor::execute($sql_query);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
            $ret[$i] = array();
            $ret[$i]['name'] =  '(' . $tab[$i]['id'] . ') '
                            . $tab[$i]['name'];
		}
		return $ret;
    }

    public function get_top_x_surnames($cnt) {
        if(!isset($cnt) || empty($cnt)) $cnt = 10;

        $sql = 'SELECT surname, count(*) AS cnt'
             . ' FROM rp_name_personal'
             . ' WHERE surname IS NOT NULL AND surname != \'\''
             . ' GROUP BY surname ORDER BY count(*) DESC LIMIT 0,'
             . $cnt;
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $rows = RP_Query_Executor::execute( $sql_query );
        return $rows;
    }
    /**
     * @todo Description of function deletePersona
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function delete_persona( $id, $batch_id ) {
        RP_Dao_Factory::get_rp_indi_note_dao( $this->prefix )
                ->delete_by_indi_id( $id, $batch_id );
        RP_Dao_Factory::get_rp_indi_event_dao( $this->prefix )
                ->delete_by_indi_id( $id, $batch_id );
        RP_Dao_Factory::get_rp_indi_cite_dao( $this->prefix )
                ->delete_by_indi_id( $id, $batch_id );
        RP_Dao_Factory::get_rp_indi_name_dao( $this->prefix )
                ->delete_by_indi_id( $id, $batch_id );
        RP_Dao_Factory::get_rp_indi_dao( $this->prefix )
                ->delete( $id, $batch_id );
    }

    public function delete_all( $batch_id ) {
        $sql_array = Array(
            "DELETE FROM rp_name_personal WHERE id IN (SELECT name_id FROM rp_indi_name WHERE indi_batch_id = ?)",
            "DELETE FROM rp_name_cite WHERE name_id NOT IN (SELECT id FROM rp_name_personal)",
            "DELETE FROM rp_name_name WHERE name_id NOT IN (SELECT id FROM rp_name_personal)",
            "DELETE FROM rp_name_note WHERE name_id  NOT IN (SELECT id FROM rp_name_personal)",
            "DELETE FROM rp_event_note WHERE event_id IN (SELECT event_id FROM rp_fam_event WHERE fam_batch_id = ?)",
            "DELETE FROM rp_event_note WHERE event_id IN (SELECT event_id FROM rp_indi_event WHERE indi_batch_id = ?)",
            "DELETE FROM rp_event_cite WHERE event_id IN (SELECT event_id FROM rp_fam_event WHERE fam_batch_id = ?)",
            "DELETE FROM rp_event_cite WHERE event_id IN (SELECT event_id FROM rp_indi_event WHERE indi_batch_id = ?)",
            "DELETE FROM rp_event_detail WHERE id IN (SELECT event_id FROM rp_indi_event WHERE indi_batch_id = ?)",
            "DELETE FROM rp_event_detail WHERE id IN (SELECT event_id FROM rp_fam_event WHERE fam_batch_id = ?)",
            "DELETE FROM rp_address  WHERE id IN (SELECT corp_addr_id FROM rp_header WHERE batch_id = ?)",
            "DELETE FROM rp_fam WHERE batch_id = ?",
            "DELETE FROM rp_fam_child WHERE fam_batch_id = ?",
            "DELETE FROM rp_fam_cite WHERE fam_batch_id = ?",
            "DELETE FROM rp_fam_event WHERE fam_batch_id = ?",
            "DELETE FROM rp_fam_note WHERE fam_batch_id = ?",
            "DELETE FROM rp_header WHERE batch_id = ?",
            "DELETE FROM rp_indi WHERE batch_id = ?",
            "DELETE FROM rp_indi_option WHERE indi_batch_id = ?",
            "DELETE FROM rp_indi_cite WHERE indi_batch_id = ?",
            "DELETE FROM rp_indi_event WHERE indi_batch_id = ?",
            "DELETE FROM rp_indi_fam WHERE indi_batch_id = ?",
            "DELETE FROM rp_indi_name WHERE indi_batch_id = ?",
            "DELETE FROM rp_indi_note WHERE indi_batch_id = ?",
            "DELETE FROM rp_note WHERE batch_id = ?",
            "DELETE FROM rp_repo WHERE batch_id = ?",
            "DELETE FROM rp_repo_note WHERE repo_batch_id = ?",
            "DELETE FROM rp_source WHERE batch_id = ?",
            "DELETE FROM rp_source_cite WHERE source_batch_id = ?",
            "DELETE FROM rp_source_note WHERE source_batch_id = ?",
            "DELETE FROM rp_submitter WHERE batch_id = ?",
            "DELETE FROM rp_submitter_note WHERE submitter_batch_id = ?"
        );

        foreach ( $sql_array AS $sql ) {
            $sql_query = new RP_Sql_Query( $sql, $this->prefix );
            if ( strpos( $sql, '?' ) !== false ) {
                $sql_query->set_number( $batch_id );
            }
            $this->execute_update( $sql_query );
        }
    }

    /**
     * @todo Description of function updatePersonaPrivacy
     * @param  $id
     * @param  $batchId
     * @param  $privacy
     * @return
     */
    public function update_persona_privacy( $id, $batch_id, $privacy, $name ) {
        $sql = 'INSERT INTO rp_indi_option'
            . ' (indi_id,indi_batch_id,privacy_code,excluded_name,update_datetime)'
            . ' VALUES (?, ?, ?, ?, now())'
            . ' ON DUPLICATE KEY UPDATE privacy_code = ?, excluded_name = ?, update_datetime = now()';
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $privacy );
        $sql_query->set( $privacy=='Exc' ? $name : '' );
        $sql_query->set( $privacy );
        $sql_query->set( $privacy=='Exc' ? $name : '' );
        return $this->execute_update( $sql_query );
    }

    /**
     * @todo Description of function getPersonaPrivacy
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_persona_privacy( $id, $batch_id ) {
        $sql = 'SELECT privacy_code'
            . ' FROM rp_indi_option rio'
            . ' WHERE rio.indi_batch_id = ? AND rio.indi_id = ?';
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $id );
        $code = $this->query_single_result( $sql_query );
        return $code;
    }

    /**
     * @todo Description of function getIndexedPageCnt
     * @param  $batchId
     * @return
     */
    public function get_indexed_page_cnt( $batch_id, $surname ) {
        $sql = 'SELECT count(*)'
            . ' FROM rp_indi ri'
            . ' LEFT OUTER JOIN rp_indi_option rio'
            . ' ON rio.indi_batch_id = ri.batch_id AND rio.indi_id = ri.id'
            . ' JOIN rp_indi_name rip'
            . ' ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id'
            . ((!empty( $surname )) ?' JOIN rp_name_personal rnp ON rip.name_id = rnp.id':'')
            . ' WHERE ri.batch_id = ? AND ri.wp_page_id IS NOT null'
            . ' AND rip.seq_nbr = 1'
            . ((!empty( $surname )) ? (" AND rnp.surname LIKE '" . esc_sql($surname) . "%'"):'')
            . " AND IFNULL(rio.privacy_code,'Def') != '"
            . RP_Persona_Helper::EXC . "'";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $cnt = $this->query_single_result( $sql_query );
        return $cnt;
    }

    public function get_batch_ids(  ) {
        $sql = 'SELECT DISTINCT batch_id FROM rp_indi';
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $rows = RP_Query_Executor::execute( $sql_query );
        $batchids = array();
        if ( $rows !== false && count($rows) > 0 ) {
            $cnt = count( $rows );
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $batchids[] = $rows[$idx]['batch_id'];
            }
        }
        return $batchids;
    }

    public function get_indexed_page( $batch_id, $surname, $page, $per_page, $set = 'paginated' ) {
        $sql = 'SELECT ri.id AS id'
                . ',rnp.surname AS surname'
                . ',rnp.given AS given'
                . ',ri.wp_page_id AS page'
                . ',rio.privacy_code'
                . ' FROM rp_indi ri'
                . ' LEFT OUTER JOIN rp_indi_option rio'
                . ' ON rio.indi_batch_id = ri.batch_id AND rio.indi_id = ri.id'
                . ' JOIN rp_indi_name rip'
                . ' ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id'
                . ' JOIN rp_name_personal rnp ON rip.name_id = rnp.id'
                . " WHERE ri.batch_id = ? AND ri.wp_page_id IS NOT null"
                . ' AND rip.seq_nbr = 1'
                . ((!empty( $surname )) ? (" AND rnp.surname LIKE '" . esc_sql($surname) . "%'"):'')
                . " AND IFNULL(rio.privacy_code,'Def') != '"
                . RP_Persona_Helper::EXC
                . "' ORDER BY rnp.surname, rnp.given";
        if( "paginated" == $set) {
                $sql .= " LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
        }
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $persons = array();
        if ( $rows > 0 ) {
            $cnt = count( $rows );
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $persona = new RP_Persona();
                $persona->surname = $rows[$idx]['surname'];
                $persona->given = $rows[$idx]['given'];
                $persona->page = $rows[$idx]['page'];
                $persona->privacy = $rows[$idx]['privacy_code'];
                $persona->birth_date = $this->get_birth_date( $batch_id, $rows[$idx]['id'] );
                $persona->death_date = $this->get_death_date( $batch_id, $rows[$idx]['id'] );
                $persons[$idx] = $persona;
            }
        }
        return $persons;
    }

    public function get_indexed_sources( $batch_id, $page, $per_page, $set = 'paginated' ) {
         $sql = 'SELECT rs.abbr AS title'
                . ',rs.wp_page_id AS page'
                . ' FROM rp_source rs'
                . " WHERE rs.batch_id = ? AND rs.wp_page_id IS NOT null"
                . " ORDER BY rs.abbr";
         if( 'paginated' == $set) {
                $sql .= " LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
         }

        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $sources = array();
        if ( $rows > 0 ) {
            $cnt = count( $rows );
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $evi = new RP_Evidence();
                $evi->title = $rows[$idx]['title'];
                $evi->page = $rows[$idx]['page'];
                $sources[$idx] = $evi;
            }
        }
        return $sources;
    }

    public function get_indexed_source_cnt( $batch_id ) {
         $sql = 'SELECT count(*)'
                . ' FROM rp_source rs'
                . ' WHERE rs.batch_id = ? AND rs.wp_page_id IS NOT null';

        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $cnt = $this->query_single_result( $sql_query );
        return $cnt;
    }

    public function get_excluded( $batch_id ) {
        $sql = 'SELECT rio.indi_id AS id,'
                . 'replace(rio.excluded_name,"/","") AS full_name'
                . ' FROM rp_indi_option rio'
                . " WHERE rio.privacy_code = '"
                . RP_Persona_Helper::EXC
                . "' AND rio.indi_batch_id = ?";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $persons = array();
        $cnt = count( $rows );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $persona = new RP_Persona();
                $persona->full_name = $rows[$idx]['full_name'];
                $persona->id = $rows[$idx]['id'];
                $persons[$idx] = $persona;
        }
        return $persons;
    }

    /**
     * @todo Description of function getBirthAndDeathDates
     * @param  $batchId
     * @param  $id
     * @return
     */
    public function get_birth_and_death_dates( $batch_id, $id ) {
        $sql = "(SELECT 'birth' AS type, event_date AS date, place"
        . " FROM rp_indi_event rie"
        . " JOIN rp_event_detail red ON red.id = rie.event_id and red.event_type = 'Birth'"
        . " WHERE rie.indi_id = ? AND rie.indi_batch_id = ? LIMIT 0,1)"
        . " UNION (SELECT 'death' AS type,  event_date As date, place"
        . " FROM rp_indi_event rie"
        . " JOIN rp_event_detail red ON red.id = rie.event_id and red.event_type = 'Death'"
        . " WHERE rie.indi_id = ? AND rie.indi_batch_id = ? LIMIT 0,1)";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        return RP_Query_Executor::execute( $sql_query );
    }

    /**
     * @todo Description of function getBirthDate
     * @param  $batchId
     * @param  $id
     * @return
     */
    public function get_birth_date( $batch_id, $id ) {
        $sql = "SELECT event_date AS birth_date"
            . " FROM rp_indi_event rie"
                . " JOIN rp_event_detail red ON red.id = rie.event_id and red.event_type = 'Birth'"
                . " WHERE rie.indi_id = ? AND rie.indi_batch_id = ? LIMIT 0,1";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        return $this->query_single_result( $sql_query );
    }

    /**
     * @todo Description of function getDeathDate
     * @param  $batchId
     * @param  $id
     * @return
     */
    public function get_death_date( $batch_id, $id ) {
        $sql = "SELECT event_date As death_date"
                . " FROM rp_indi_event rie"
                . " JOIN rp_event_detail red ON red.id = rie.event_id and red.event_type = 'Death'"
                . " WHERE rie.indi_id = ? AND rie.indi_batch_id = ? LIMIT 0,1";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        return $this->query_single_result( $sql_query );
    }

    /**
     * @todo Description of function get_fullname
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_fullname( $id, $batch_id ) {
        $sql = "SELECT trim(replace(rnp.personal_name,'/',' ')) AS fullname"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id"
            . " JOIN rp_name_personal rnp ON rip.name_id = rnp.id"
            . " WHERE ri.id = ? AND ri.batch_id = ? LIMIT 0,1";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        return $this->query_single_result( $sql_query );
    }

    /**
     * @todo Description of function getPage
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_page( $id, $batch_id ) {
        $sql = "SELECT ri.wp_page_id AS page" . " FROM rp_indi ri"
                . " WHERE ri.id = ? AND ri.batch_id = ?";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        return $this->query_single_result( $sql_query );
    }

    /**
     * @todo Description of function getPersonsNoPage
     * @param  $batchId
     * @return
     */
    public function get_persons_no_page( $batch_id ) {
        $sql = "SELECT ri.id AS id, ri.batch_id AS batch_id"
        . ", rnp.given AS given, rnp.surname AS surname"
        . " FROM rp_indi ri"
        . " LEFT OUTER JOIN rp_indi_option rio ON rio.indi_batch_id = ri.id AND rio.indi_id = ri.batch_id"
        . " JOIN rp_indi_name rip ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id"
        . " JOIN rp_name_personal rnp ON rip.name_id = rnp.id"
        . " WHERE ri.wp_page_id IS null AND ri.batch_id = ? AND rip.seq_nbr = 1"
        . " AND rio.privacy_code IS null OR rio.privacy_code != '"
        . RP_Persona_Helper::EXC . "' ORDER BY rnp.surname,rnp.given";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $persons = array();
        if ( $rows > 0 ) {
            $cnt = count( $rows );
            for ( $idx = 0;
            $idx < $cnt;
            $idx++ ) {
                $person = array();
                $person['id'] = $rows[$idx]['id'];
                $person['batch_id'] = $rows[$idx]['batch_id'];
                $person['given'] = $rows[$idx]['given'];
                $person['surname'] = $rows[$idx]['surname'];
                $persons[$idx] = $person;
            }
        }
        return $persons;
    }

    /**
     * @todo Description of function getPersona
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_persona( $id, $batch_id ) {
        $persona = null;
        if ( ! isset( $id ) || empty( $id ) || $id == '0' ) {
            $persona = RP_Persona_Helper::get_unknown();
        } else if ( isset( self::$_pcache[$batch_id] )
        && isset( self::$_pcache[$batch_id][$id] ) ) {
            $persona = &self::$_pcache[$batch_id][$id];
        } else {
            $sql1 = "SELECT ri.id AS id, ri.batch_id AS batch_id,"
            . "IFNULL(rio.privacy_code,'Def') AS privacy"
            . ",trim(replace(rnp.personal_name,'/',' ')) AS full_name"
            . ",rnp.prefix AS prefix"
            . ",rnp.surname AS surname"
            . ",rnp.given AS given"
            . ",rnp.nickname AS nickname"
            . ",rnp.surname_prefix AS surname_prefix"
            . ",rnp.suffix AS suffix"
            . ",ri.gender AS gender"
            . ",rf.spouse1 AS father"
            . ",rf.spouse2 AS mother"
            . ",rfc.fam_id AS famc"
            . ",ri.wp_page_id AS page"
            . " FROM rp_indi ri"
            . " LEFT OUTER JOIN rp_indi_option rio ON rio.indi_id = ri.id AND rio.indi_batch_id = ri.batch_id"
            . " JOIN rp_indi_name rip ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id AND rip.seq_nbr = 1"
            . " JOIN rp_name_personal rnp ON rip.name_id = rnp.id"
            . " LEFT OUTER JOIN rp_fam_child rfc ON ri.id = rfc.child_id AND ri.batch_id = rfc.indi_batch_id"
            . " LEFT OUTER JOIN rp_fam rf ON rfc.fam_id = rf.id AND rfc.fam_batch_id = rf.batch_id"
            . " WHERE ri.id = ? AND ri.batch_id = ? "
            . " AND IFNULL(rio.privacy_code,'Def') != '"
            . RP_Persona_Helper::EXC . "'";
            $sql_query = new RP_Sql_Query( $sql1, $this->prefix );
            $sql_query->set( $id );
            $sql_query->set_number( $batch_id );
            $persona = $this->get_row( $sql_query );
            if ( ! isset( $persona )
            || empty( $persona ) ) {
                $persona = RP_Persona_Helper::get_unknown();
            } else {
                $rows = $this->get_birth_and_death_dates( $batch_id, $id );
                if ( isset( $rows[0] ) ) {
                    if ( $rows[0]['type'] == 'birth' ) {
                        $persona->birth_date = $rows[0]['date'];
                        $persona->birth_place = $rows[0]['place'];
                        if ( isset( $rows[1] ) ) {
                            $persona->death_date = $rows[1]['date'];
                            $persona->death_place = $rows[1]['place'];
                        }

                    } else {
                        $persona->death_date = $rows[0]['date'];
                        $persona->death_place = $rows[0]['place'];
                        if ( isset( $rows[1] ) ) {
                            $persona->birth_date = $rows[1]['date'];
                            $persona->birth_place = $rows[1]['place'];
                        }
                    }
                }
            }
            if ( ! isset( self::$_pcache[$batch_id] ) ) {
                self::$_pcache[$batch_id] = array();
            }
            self::$_pcache[$batch_id][$id] = $persona;
            $persona = &self::$_pcache[$batch_id][$id];
        }
        return $persona;
    }

    /**
     * @todo Description of function getPersonaEvents
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_persona_events( $id, $batch_id ) {
        $sql = "SELECT event_type, event_date, place, classification, cause"
            . " FROM rp_indi_event rie"
            . " JOIN rp_event_detail red ON red.id = rie.event_id"
            . " WHERE rie.indi_id = ? AND rie.indi_batch_id = ?";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $events = null;
        if ( $rows > 0 ) {
            $events = array();
            for ( $idx = 0;
            $idx < $cnt;
            $idx++ ) {
                $event = array();
                $event['type'] = $rows[$idx]['event_type'];
                $event['date'] = $rows[$idx]['event_date'];
                $event['place'] = $rows[$idx]['place'];
                $event['classification'] = $rows[$idx]['classification'];
                $event['cause'] = $rows[$idx]['cause'];
                $event['associated_person'] = null; //array('name'=>'', 'isPrivate'=> false);
                $events[$idx] = $event;
            }
        }
        return $events;
    }

    /**
     * @todo Description of function getMarriages
     * @param  $persona
     * @return
     */
    public function get_marriages( $persona ) {
        $id = $persona->id;
        $batch_id = $persona->batch_id;
        $families = null;
        if ( isset( self::$_fcache[$batch_id] )
        && isset( self::$_fcache[$batch_id][$id] ) ) {
            $families = & self::$_fcache[$batch_id][$id];
        } else {
            $sql = "SELECT rf.id as id, rf.spouse1 AS s1, rf.spouse2 AS s2,"
                . "red.event_date AS event_date, red.place AS place"
                . " FROM rp_fam rf"
                . " LEFT OUTER JOIN rp_fam_event rfe ON rf.id = rfe.fam_id"
                . " AND rf.batch_id = rfe.fam_batch_id"
                . " LEFT OUTER JOIN rp_event_detail red ON red.id = rfe.event_id"
                . " WHERE ((rf.spouse1 = ? AND rf.indi_batch_id_1 = ?)"
                . " OR (rf.spouse2 = ? AND rf.indi_batch_id_2 = ?))"
                . " AND (red.event_type = ? OR red.event_type IS null)";
            $sql_query = new RP_Sql_Query( $sql, $this->prefix );
            $sql_query->set( $id );
            $sql_query->set_number( $batch_id );
            $sql_query->set( $id );
            $sql_query->set_number( $batch_id );
            $sql_query->set( 'Marriage' );
            $rows = RP_Query_Executor::execute( $sql_query );
            $cnt = count( $rows );
            $families = null;
            if ( $cnt > 0 ) {
                $families = array();
                for ( $idx = 0;
                $idx < $cnt;
                $idx++ ) {
                    $event = array();
                    $event['fams'] = $rows[$idx]['id'];
                    $event['type'] = 'Marriage';
                    $event['date'] = $rows[$idx]['event_date'];
                    $event['place'] = $rows[$idx]['place'];
                    $id1 = $rows[$idx]['s1'];
                    $id2 = $rows[$idx]['s2'];
                    if ( $id1 == $persona->id ) {
                        $event['spouse1'] = $persona;
                        $event['associated_person_id'] = $id2;
                    } else {
                        $event['spouse2'] = $persona;
                        $event['associated_person_id'] = $id1;
                    }
                    $families[$idx] = $event;
                }
                if ( ! isset( self::$_fcache[$batch_id] ) )self::$_fcache[$batch_id] = array();
                self::$_fcache[$batch_id][$id] = $families;
                $families = & self::$_fcache[$batch_id][$id];
            }
        }
        return $families;
    }

    /**
     * @todo Description of function getChildren
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_children( $id, $batch_id ) {
        $sql = "SELECT rfc.child_id AS kid"
            . " FROM rp_fam_child rfc"
            . " WHERE rfc.fam_id = ? AND rfc.fam_batch_id = ?";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $kids = null;
        if ( $rows > 0 ) {
            $kids = array();
            for ( $idx = 0;
            $idx < $cnt;
            $idx++ ) {
                $kids[] = $rows[$idx]['kid'];
            }
        }
        return $kids;
    }

    /**
     * @todo Description of function getPersonaSources
     * @param  $id
     * @param  $batchId
     * @return
     */
    public function get_persona_sources( $id, $batch_id ) {
        $sql = 'SELECT DISTINCT id,page,title,abbr FROM'
            . ' (SELECT rs.id AS id, rs.wp_page_id AS page,rs.source_title AS title,rs.abbr AS abbr'
                . ' FROM rp_indi_cite ric'
                . ' JOIN rp_source_cite rsc ON ric.cite_id = rsc.id'
                . ' JOIN rp_source rs ON rs.id = rsc.source_id AND rs.batch_id = rsc.source_batch_id'
                . ' WHERE ric.indi_id = ? AND ric.indi_batch_id = ?'
            . ' UNION'
            . ' SELECT rs.id AS id, rs.wp_page_id AS page,rs.source_title AS title,rs.abbr AS abbr'
                . ' FROM rp_indi_event rie'
                . ' JOIN rp_event_cite rec ON rie.event_id = rec.event_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rec.cite_id'
                . ' JOIN rp_source rs ON rs.id = rsc.source_id AND rs.batch_id = rsc.source_batch_id'
                . ' WHERE rie.indi_id = ? AND rie.indi_batch_id = ?'
            . ' UNION'
            . ' SELECT rs.id AS id, rs.wp_page_id AS page,rs.source_title AS title,rs.abbr AS abbr'
                . ' FROM rp_indi_fam rif'
                . ' JOIN rp_fam_cite rfc ON rfc.fam_id = rif.fam_id AND rfc.fam_batch_id = rif.fam_batch_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rfc.cite_id'
                . ' JOIN rp_source rs ON rs.id = rsc.source_id AND rs.batch_id = rsc.source_batch_id'
                . ' WHERE rif.indi_id = ? AND rif.indi_batch_id = ?'
            . ' UNION'
            . ' SELECT rs.id AS id, rs.wp_page_id AS page,rs.source_title AS title,rs.abbr AS abbr'
                . ' FROM rp_indi_name rin'
                . ' JOIN rp_name_cite rnc ON rnc.name_id = rin.name_id'
                . ' JOIN rp_source_cite rsc ON rsc.id = rnc.cite_id'
                . ' JOIN rp_source rs ON rs.id = rsc.source_id AND rs.batch_id = rsc.source_batch_id'
                . ' WHERE rin.indi_id = ? AND rin.indi_batch_id = ? ) AS t1';
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $sql_query->set( $id );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $sources = null;
        if ( $cnt > 0 ) {
            $sources = array();
            for ( $idx = 0;
            $idx < $cnt;
            $idx++ ) {
                $src = array();
                $src['src_id'] = $rows[$idx]['id'];
                $src['src_title'] = $rows[$idx]['title'];
                $src['src_abbr'] = $rows[$idx]['abbr'];
                $src['page_id'] = $rows[$idx]['page'];
                $sources[$idx] = $src;
            }
        }
        return $sources;
    }

    public function get_persons_with_pages( $batch_id ) {
        $sql = "SELECT ri.id AS id, ri.batch_id AS batch_id"
            . ",trim(replace(rnp.personal_name,'/',' ')) AS full_name"
            . ",ri.wp_page_id AS page"
            . " FROM rp_indi ri"
            . " JOIN rp_indi_name rip ON ri.id = rip.indi_id AND ri.batch_id = rip.indi_batch_id AND rip.seq_nbr = 1"
            . " JOIN rp_name_personal rnp ON rip.name_id = rnp.id"
            . " WHERE ri.wp_page_id IS NOT NULL AND ri.batch_id=?";
        $sql_query = new RP_Sql_Query( $sql, $this->prefix );
        $sql_query->set_number( $batch_id );
        $rows = RP_Query_Executor::execute( $sql_query );
        $cnt = count( $rows );
        $persons = array();
        if ( $cnt > 0 ) {
            for ( $idx = 0; $idx < $cnt; $idx++ ) {
                $p = array();
                $p['id'] = $rows[$idx]['id'];
                $p['batch_id'] = $rows[$idx]['batch_id'];
                $p['name'] = $rows[$idx]['full_name'];
                $p['page_id'] = $rows[$idx]['page'];
                $persons[$idx] = $p;
            }
        }
        return $persons;
    }

    /**
     * @todo Description of function readRow
     * @param  $row
     * @return
     */
    protected function read_row( $row ) {
        $rp_persona = new RP_Persona();
        $rp_persona->id = $row['id'];
        $rp_persona->batch_id = $row['batch_id'];
        $rp_persona->gender = $row['gender'];
        $rp_persona->full_name = $row['full_name'];
        $rp_persona->surname = $row['surname'];
        $rp_persona->given = $row['given'];
        $rp_persona->nickname = $row['nickname'];
        $rp_persona->surname_prefix = $row['surname_prefix'];
        $rp_persona->prefix = $row['prefix'];
        $rp_persona->suffix = $row['suffix'];
        $rp_persona->father = $row['father'];
        $rp_persona->mother = $row['mother'];
        $rp_persona->famc = $row['famc'];
        $rp_persona->page = $row['page'];
        $rp_persona->privacy = $row['privacy'];
        return $rp_persona;
    }
}
