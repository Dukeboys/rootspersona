<?php
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Connection.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Connection-Factory.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Query-Executor.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Transaction.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Sql-Query.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/sql/class-RP-Credentials.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/core/class-RP-Array-List.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Addr.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Address-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Event-Cite.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Event-Cite-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Event-Detail.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Event-Detail-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Event-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Event-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Fam.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Fam-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Fam-Child.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Fam-Child-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Fam-Cite.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Fam-Cite-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Fam-Event.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Fam-Event-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Fam-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Fam-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Header.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Header-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi-Cite.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Cite-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi-Event.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Event-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi-Fam.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Fam-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi-Name.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Name-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Indi-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Indi-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Name-Cite.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Name-Cite-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Name-Name.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Name-Name-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Name-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Name-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Name-Personal.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Name-Personal-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Note-Rec.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Repo-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Repo-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Source.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Source-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Source-Cite.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Source-Cite-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Source-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Source-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Submitter.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Submitter-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Submitter-Note.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Submitter-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Persona-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Simple-Person.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Persona.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/dao/mysql/class-RP-Submitter-Note-Mysql-DAO.php' );
require_once( WP_PLUGIN_DIR . '/rootspersona/php/model/class-RP-Evidence.php' );

/**
 * DaoFactory
 * @author: http://phpdao.com
 * @date: ${date}
 */
class RP_Dao_Factory {


	/**
	 * @todo Description of function getRpPersonaDao
	 * @param  $prefix
	 * @return
	 */
	public static function get_rp_persona_dao( $prefix ) {
		return new RP_Persona_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpAddressDao
	 */
	public static function get_rp_address_dao( $prefix ) {
		return new RP_Address_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpEventCiteDao
	 */
	public static function get_rp_event_cite_dao( $prefix ) {
		return new RP_Event_Cite_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpEventDetailDao
	 */
	public static function get_rp_event_detail_dao( $prefix ) {
		return new RP_Event_Detail_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpEventNoteDao
	 */
	public static function get_rp_event_note_dao( $prefix ) {
		return new RP_Event_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpFamDao
	 */
	public static function get_rp_fam_dao( $prefix ) {
		return new RP_Fam_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpFamChildDao
	 */
	public static function get_rp_fam_child_dao( $prefix ) {
		return new RP_Fam_Child_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpFamCiteDao
	 */
	public static function get_rp_fam_cite_dao( $prefix ) {
		return new RP_Fam_Cite_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpFamEventDao
	 */
	public static function get_rp_fam_event_dao( $prefix ) {
		return new RP_Fam_Event_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpHeaderDao
	 */
	public static function get_rp_header_dao( $prefix ) {
		return new RP_Header_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiDao
	 */
	public static function get_rp_indi_dao( $prefix ) {
		return new RP_Indi_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiCiteDao
	 */
	public static function get_rp_indi_cite_dao( $prefix ) {
		return new RP_Indi_Cite_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiEventDao
	 */
	public static function get_rp_indi_event_dao( $prefix ) {
		return new RP_Indi_Event_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiFamDao
	 */
	public static function get_rp_indi_fam_dao( $prefix ) {
		return new RP_Indi_Fam_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiNameDao
	 */
	public static function get_rp_indi_name_dao( $prefix ) {
		return new RP_Indi_Name_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpIndiNoteDao
	 */
	public static function get_rp_indi_note_dao( $prefix ) {
		return new RP_Indi_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpNameCiteDao
	 */
	public static function get_rp_name_cite_dao( $prefix ) {
		return new RP_Name_Cite_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpNameNameDao
	 */
	public static function get_rp_name_name_dao( $prefix ) {
		return new RP_Name_Name_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpNameNoteDao
	 */
	public static function get_rp_name_note_dao( $prefix ) {
		return new RP_Name_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpNamePersonalDao
	 */
	public static function get_rp_name_personal_dao( $prefix ) {
		return new RP_Name_Personal_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpNoteDao
	 */
	public static function get_rp_note_dao( $prefix ) {
		return new RP_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpRepoDao
	 */
	public static function get_rp_repo_dao( $prefix ) {
		return new RP_Repo_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpRepoNoteDao
	 */
	public static function get_rp_repo_note_dao( $prefix ) {
		return new RP_Repo_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpSourceDao
	 */
	public static function get_rp_source_dao( $prefix ) {
		return new RP_Source_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpSourceCiteDao
	 */
	public static function get_rp_source_cite_dao( $prefix ) {
		return new RP_Source_Cite_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpSourceNoteDao
	 */
	public static function get_rp_source_note_dao( $prefix ) {
		return new RP_Source_Note_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpSubmitterDao
	 */
	public static function get_rp_submitter_dao( $prefix ) {
		return new RP_Submitter_Mysql_Dao( $prefix );
	}
	/**
	 * @return RpSubmitterNoteDao
	 */
	public static function get_rp_submitter_note_dao( $prefix ) {
		return new RP_Submitter_Note_Mysql_Dao( $prefix );
	}
}
?>
