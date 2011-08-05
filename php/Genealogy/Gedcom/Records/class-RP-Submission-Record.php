<?php
/**
 * \Genealogy\Gedcom\Records\SubmissionRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SubmissionRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * SubmissionRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Submission_Record extends RP_Record_Abstract {
	/**
	 *
	 * @var string
	 */
	var $id;
	/**
	 *
	 * @var string
	 */
	var $family_file_name;
	/**
	 *
	 * @var string
	 */
	var $temple_code;
	/**
	 *
	 * @var int
	 */
	var $generations_ancestors;
	/**
	 *
	 * @var int
	 */
	var $generations_descendants;
	/**
	 *
	 * @var string
	 */
	var $ordinance_process_flag;
	/**
	 *
	 * @var string
	 */
	var $submitter_id;
	/**
	 *
	 * @var string
	 */
	var $auto_rec_id;
	/**
	 *
	 * @var string
	 */
	var $change_date;
	/**
	 *
	 * @var array
	 */
	var $notes = array();
	/**
	 * Initializes complex attributes
	 *
	 * @return none
	 */
	public function __construct() {
		$this->change_date = new RP_Change_Date();
	}
	/**
	 * Flattens the object into a GEDCOM compliant format
	 *
	 * This method guarantees compliance, not re-creation of
	 * the original order of the records.
	 *
	 * @param int    $lvl indicates the level at which this record
	 *                    should be generated
	 * @param string $ver represents the version of the GEDCOM standard
	 *
	 * @return string a return character delimited string of gedcom records
	 *
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function to_gedcom( $lvl, $ver ) {
		$ged_rec = '';
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			$ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::SUBMISSION;
			$lvl2 = $lvl + 1;
			if ( isset( $this->family_file_name )
			&& $this->family_file_name != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::FAMILYFILE . ' ' . $this->family_file_name;
			}
			if ( isset( $this->temple_code )
			&& $this->temple_code != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::TEMPLECODE . ' ' . $this->temple_code;
			}
			if ( isset( $this->generations_ancestors )
			&& $this->generations_ancestors != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::ANCESTORS . ' ' . $this->generations_ancestors;
			}
			if ( isset( $this->generations_descendants )
			&& $this->generations_descendants != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DESCENDANTS . ' ' . $this->generations_descendants;
			}
			if ( isset( $this->ordinance_process_flag )
			&& $this->ordinance_process_flag != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::ORDINANCEFLAG . ' ' . $this->ordinance_process_flag;
			}
			$ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->change_date_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->note_to_gedcom( $ged_rec, $lvl2, $ver );
		}
		return $ged_rec;
	}
	/**
	 * Extracts attribute contents FROM a parent tree object
	 *
	 * @param array  $tree an array containing an array FROM which the
	 *                     object data should be extracted
	 * @param string $ver  represents the version of the GEDCOM standard
	 *                     data is being extracted from
	 *
	 * @return void
	 *
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function parse_tree( $tree, $ver ) {
		$this->id = parent::parse_ref_id( $tree[0], Rp_Tags::SUBMISSION );
		if ( isset( $tree[0][1] ) ) {
			$sub2 = $tree[0][1];
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SUBMITTER ) ) !== false ) {
				$this->submitter_id = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::SUBMITTER );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::FAMILYFILE ) ) !== false ) {
				$this->family_file_name = parent::parse_text( $sub2[$i1], Rp_Tags::FAMILYFILE );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::TEMPLECODE ) ) !== false ) {
				$this->temple_code = parent::parse_text( $sub2[$i1], Rp_Tags::TEMPLECODE );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ANCESTORS ) ) !== false ) {
				$this->generations_ancestors = parent::parse_text( $sub2[$i1], Rp_Tags::ANCESTORS );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::DESCENDANTS ) ) !== false ) {
				$this->generations_descendants = parent::parse_text( $sub2[$i1], Rp_Tags::DESCENDANTS );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ORDINANCEFLAG ) ) !== false ) {
				$this->ordinance_process_flag = parent::parse_text( $sub2[$i1], Rp_Tags::ORDINANCEFLAG );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::AUTORECID ) ) !== false ) {
				$this->auto_rec_id = parent::parse_text( $sub2[$i1], Rp_Tags::AUTORECID );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CHANGEDATE ) ) !== false ) {
				$this->change_date->parse_tree( array( $sub2[$i1] ), $ver );
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::NOTE, $off ) ) !== false ) {
				$tmp = new RP_Note();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->notes[] = $tmp;
	$off = $i1 + 1;
			}
		}
	}
	/**
	 * Creates a string representation of the object
	 *
	 * @return string  contains string representation of each
	 *                 public field in the object
	 *
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function __toString() {
		$str = __CLASS__ . '(Id->' . $this->id . ', SubmitterId->' . $this->submitter_id . ', FamilyFileName->' . $this->family_file_name . ', TempleCode->' . $this->temple_code . ', GenerationsAncestors->' . $this->generations_ancestors . ', GenerationsDescendants->' . $this->generations_descendants . ', OrdinanceProcessFlag->' . $this->ordinance_process_flag . ', AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', Notes->(';
		for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
			$str .= "\n" . $this->notes[$i];
		}
		$str .= '))';
		return $str;
	}
}
?>
