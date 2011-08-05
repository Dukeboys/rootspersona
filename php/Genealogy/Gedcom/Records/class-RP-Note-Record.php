<?php
/**
 * \Genealogy\Gedcom\Records\NoteRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: NoteRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * NoteRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Note_Record extends RP_Record_Abstract {
	/**
	 *
	 * @var string
	 */
	var $id;
	/**
	 *
	 * @var string
	 */
	var $text;
	/**
	 *
	 * @var array
	 */
	var $user_ref_nbrs = array();
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
			$ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::NOTE;
			if ( isset( $this->text )
			&& $this->text != '' ) {
				$ged_rec .= ' ' . parent::to_con_tag( $this->text, null, $lvl );
			}
			$lvl2 = $lvl + 1;
			$ged_rec = $this->user_ref_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->change_date_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->cite_to_gedcom( $ged_rec, $lvl2, $ver );
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
		$this->id = parent::parse_ref_id( $tree[0], Rp_Tags::NOTE );
		$this->text = parent::parse_con_tag( $tree[0], Rp_Tags::NOTE );
		if ( isset( $tree[0][1] ) ) {
			$sub2 = $tree[0][1];
			$this->user_file_parse_tree( $sub2, $ver );
			$this->auto_rec_parse_tree( $sub2, $ver );
			$this->change_date_parse_tree( $sub2, $ver );
			$this->cite_parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(Id->' . $this->id . ', Text->' . $this->text;
		$str .= ', UserRefNbrs->';
		for ( $i = 0;
	$i < count( $this->user_ref_nbrs );
	$i++ ) {
			$str .= 'UserRefNbr->' . $this->user_ref_nbrs[$i]['Nbr'] . ' (' . $this->user_ref_nbrs[$i]['Type'] . ')';
		}
		$str .= ', AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', Citations->(';
		for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
			$str .= "\n" . $this->citations[$i];
		}
		$str .= '))';
		return $str;
	}
}
?>
