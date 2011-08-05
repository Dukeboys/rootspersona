<?php
/**
 * \Genealogy\Gedcom\Association
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Association.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * Citation class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Association extends RP_Entity_Abstract {
	/**
	 *
	 * @var string
	 */
	var $associate_id;
	/**
	 *
	 * @var string
	 */
	var $relationship;
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
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		$ged_rec = '';
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			if ( isset( $this->associate_id )
			&& $this->associate_id != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::ASSOCIATION . ' @' . $this->associate_id . '@';
				$lvl2 = $lvl + 1;
				if ( isset( $this->relationship )
				&& $this->relationship != '' ) {
					$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::RELATIONSHIP . ' ' . $this->relationship;
				}
				for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
					$ged_rec .= "\n" . $this->citations[$i]->to_gedcom( $lvl2, $ver );
				}
				for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
					$ged_rec .= "\n" . $this->notes[$i]->to_gedcom( $lvl2, $ver );
				}
			}
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::ASSOCIATION ) ) !== false ) {
			$this->associate_id = parent::parse_ptr_id( $tree[$i1], Rp_Tags::ASSOCIATION );
			if ( isset( $tree[$i1][1] ) ) {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::RELATIONSHIP ) ) !== false ) {
					$this->relationship = parent::parse_text( $sub2[$i2], Rp_Tags::RELATIONSHIP );
				}
				$off = 0;
				while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CITE, $off ) ) !== false ) {
					$tmp = new RP_Citation();
					$tmp->parse_tree( array( $sub2[$i1] ), $ver );
					$this->citations[] = $tmp;
	$off = $i1 + 1;
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
		$str = __CLASS__ . '(Version->' . $this->ver . ', AssociateId->' . $this->associate_id . ', Relationship->' . $this->relationship . ', Citations->(';
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
