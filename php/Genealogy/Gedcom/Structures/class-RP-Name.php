<?php
/**
 * \Genealogy\Gedcom\Name
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Name.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link
 */
/**
 * Name class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Name extends RP_Entity_Abstract {
	/**
	 *
	 * @var string
	 */
	var $full;
	/**
	 *
	 * @var string
	 */
	var $type;
	/**
	 *
	 * @var NamePieces
	 */
	var $pieces;
	/**
	 * constructor
	 */
	function __construct() {
		$this->pieces = new RP_Name_Pieces();
	}
	/**
	 * Returns a concatenated string of a 'Full' name
	 *
	 * @return string The full name
	 */
	public function get_full_name() {
		if ( isset( $this->full )
		&& $this->full != '' ) {
			$str = $this->full;
		} else {
			$str = $this->pieces->get_full_name();
			$this->full = $str;
		}
		return $str;
	}
	/**
	 * Returns a surname deriving it FROM a Fullname if a Surname does not exist.
	 *
	 * @return string The surname
	 */
	public function get_surname() {
		if ( isset( $this->pieces->surname )
		&& $this->pieces->surname != '' ) {
			$str = $this->pieces->surname;
		} else {
			$str = $this->get_full_name();
			$str = substr( $str, strpos( $str, '/' ) + 1 );
			$str = substr( $str, 0, strpos( $str, '/' ) );
			$str = ( empty( $str ) ? substr( $str, Strrpos( $str, ' ' ) ) : $str );
		}
		return $str;
	}
	

	/**
	 * @todo Description of function getGiven
	 * @param 
	 * @return 
	 */
	public function get_given() {
		if ( isset( $this->pieces->given )
		&& $this->pieces->given != '' ) {
			$str = $this->pieces->given;
		} else {
			$str = $this->get_full_name();
			$str = substr( $str, 0, strpos( $str, '/' ) );
			//$str = substr($str,0,strpos($str, '/'));
			$str = ( empty( $str ) ? substr( $str, Strrpos( $str, ' ' ) ) : $str );
		}
		return $str;
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
	 * @param string $top relevant top lvl tag name
	 *
	 * @return string a return character delimited string of gedcom records
	 *
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function to_gedcom( $lvl, $ver, $top = Rp_Tags::FULL ) {
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		$ged_rec = '';
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			if ( isset( $this->full )
			&& $this->full != '' ) {
				$ged_rec .= $lvl . ' ' . $top . ' ' . $this->full;
			}
			$lvl2 = $lvl + 1;
			if ( isset( $this->type )
			&& $this->type != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl2 . ' ' . Rp_Tags::TYPE . ' ' . $this->type;
			}
			$str = $this->pieces->to_gedcom( $lvl2, $ver );
			if ( isset( $str )
			&& $str != '' ) {
				$ged_rec .= "\n" . $str;
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
	 * @param string $top  relevant top lvl tag name
	 *
	 * @return void
	 *
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function parse_tree( $tree, $ver, $top = Rp_Tags::FULL ) {
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, $top ) ) !== false ) {
			$this->full = parent::parse_text( $tree[$i1], $top );
			if ( isset( $tree[$i1][1] ) ) {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::TYPE ) ) !== false ) {
					$this->type = parent::parse_text( $sub2[$i2], Rp_Tags::TYPE );
				}
				$this->pieces->parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(Version->' . $this->ver . ', Full->' . $this->full . ', Type->' . $this->type . ', Pieces->' . $this->pieces . ')';
		return $str;
	}
}
