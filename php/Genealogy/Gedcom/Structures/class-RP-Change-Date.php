<?php
/**
 * \Genealogy\Gedcom\ChangeDate
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: ChangeDate.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RP_Change_Date extends RP_Entity_Abstract {
	/**
	 *
	 * @var string
	 */
	var $date;
	/**
	 *
	 * @var string
	 */
	var $time;
	/**
	 *
	 * @var array
	 */
	var $notes = array();
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
			if ( isset( $this->date )
			&& $this->date != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::CHANGEDATE;
				$lvl2 = $lvl + 1;
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DATE . ' ' . $this->date;
				if ( isset( $this->time )
				&& $this->time != '' ) {
					$ged_rec .= "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::TIME . ' ' . $this->time;
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::CHANGEDATE ) ) !== false ) {
			$sub2 = $tree[$i1][1];
			if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::DATE ) ) !== false ) {
				$this->date = parent::parse_text( $sub2[$i2], Rp_Tags::DATE );
				if ( isset( $sub2[$i2][1] ) ) {
					if ( ( $i3 = parent::find_tag( $sub2[$i2][1], Rp_Tags::TIME ) ) !== false ) {
						$this->time = parent::parse_text( $sub2[$i2][1][$i3], Rp_Tags::TIME );
					}
				}
				$off = 0;
				while ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::NOTE, $off ) ) !== false ) {
					$tmp = new RP_Note();
					$tmp->parse_tree( array( $sub2[$i2] ), $ver );
					$this->notes[] = $tmp;
	$off = $i2 + 1;
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
		$str = __CLASS__ . '(Date->' . $this->date . ' ' . $this->time . ', Notes->(';
		for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
			$str .= "\n" . $this->notes[$i];
		}
		$str .= '))';
		return $str;
	}
}
