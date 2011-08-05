<?php
/**
 * \Genealogy\Gedcom\Records\MediaRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: MediaRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * MediaRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Media_Record extends RP_Record_Abstract {
	/**
	 *
	 * @var string
	 */
	var $id;
	/**
	 *
	 * @var array
	 */
	var $media_files = array();
	/**
	 *
	 * @var array
	 */
	var $user_ref_nbrs = array();
	/**
	 *
	 * @var array
	 */
	var $submitter_links = array();
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
	 *
	 * @var array
	 */
	var $citations = array();
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
			$ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::MEDIA;
			$lvl2 = $lvl + 1;
			for ( $i = 0;
	$i < count( $this->media_files );
	$i++ ) {
				$ged_rec .= "\n" . $this->media_files[$i]->to_gedcom( $lvl2, $ver );
			}
			$ged_rec = $this->user_ref_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->change_date_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->cite_to_gedcom( $ged_rec, $lvl2, $ver );
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
		$this->id = parent::parse_ref_id( $tree[0], Rp_Tags::MEDIA );
		if ( isset( $tree[0][1] ) ) {
			$sub2 = $tree[0][1];
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::FILE, $off ) ) !== false ) {
				$tmp = new RP_Media_File();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->media_files[] = $tmp;
	$off = $i1 + 1;
			}
			$this->user_file_parse_tree( $sub2, $ver );
			$this->auto_rec_parse_tree( $sub2, $ver );
			$this->change_date_parse_tree( $sub2, $ver );
			$this->cite_parse_tree( $sub2, $ver );
			$this->note_parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(Id->' . $this->id . ', MediaFiles->(';
		for ( $i = 0;
	$i < count( $this->media_files );
	$i++ ) {
			$str .= "MediaFile->" . $this->media_files[$i];
		}
		$str .= '), UserRefNbrs->(';
		for ( $i = 0;
	$i < count( $this->user_ref_nbrs );
	$i++ ) {
			$str .= 'UserRefNbr->' . $this->user_ref_nbrs[$i]['Nbr'] . ' (' . $this->user_ref_nbrs[$i]['Type'] . ')';
		}
		$str .= '), AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', Citations->(';
		for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
			$str .= "\n" . $this->citations[$i];
		}
		$str .= '), Notes->(';
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
