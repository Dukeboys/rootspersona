<?php
/**
 * \Genealogy\Gedcom\NamePieces
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: NamePieces.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link
 */
/**
 * NamePieces class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Name_Pieces extends RP_Entity_Abstract {
	var $prefix;
	var $given;
	var $nick_name;
	var $surname_prefix;
	var $surname;
	var $suffix;
	var $citations = array();
	var $notes = array();
	/**
	 * Returns a concatenated string of a 'Full' name
	 *
	 * @return string The full name built FROM the individiual pieces.
	 */
	public function get_full_name() {
		$str = $this->prefix . ' ' . $this->given . ( isset( $this->nick_name ) ? ' (' . $this->$_nick_name . ')' : '' ) . ' ' . $this->surname_prefix . ' ' . $this->surname . ' ' . $this->suffix;
		return Trim( str_replace( '  ', ' ', $str ) );
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
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		$ged_rec = '';
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			if ( isset( $this->prefix )
			&& $this->prefix != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::PREFIX . ' ' . $this->prefix;
			}
			if ( isset( $this->given )
			&& $this->given != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl . ' ' . Rp_Tags::GIVEN . ' ' . $this->given;
			}
			if ( isset( $this->nick_name )
			&& $this->nick_name != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl . ' ' . Rp_Tags::NICK . ' ' . $this->nick_name;
			}
			if ( isset( $this->surname_prefix )
			&& $this->surname_prefix != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl . ' ' . Rp_Tags::SURPREFIX . ' ' . $this->surname_prefix;
			}
			if ( isset( $this->surname )
			&& $this->surname != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl . ' ' . Rp_Tags::SURNAME . ' ' . $this->surname;
			}
			if ( isset( $this->suffix )
			&& $this->suffix != '' ) {
				if ( $ged_rec != '' ) {
					$ged_rec .= "\n";
				}
				$ged_rec .= $lvl . ' ' . Rp_Tags::SUFFIX . ' ' . $this->suffix;
			}
			for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
				$str .= "\n" . $this->citations[$i]->to_gedcom( $lvl, $ver );
			}
			for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
				$str .= "\n" . $this->notes[$i]->to_gedcom( $lvl, $ver );
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::PREFIX ) ) !== false ) {
			$this->prefix = parent::parse_text( $tree[$i1], Rp_Tags::PREFIX );
		}
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::GIVEN ) ) !== false ) {
			$this->given = parent::parse_text( $tree[$i1], Rp_Tags::GIVEN );
		}
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::NICK ) ) !== false ) {
			$this->nick_name = parent::parse_text( $tree[$i1], Rp_Tags::NICK );
		}
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::SURPREFIX ) ) !== false ) {
			$this->surname_prefix = parent::parse_text( $tree[$i1], Rp_Tags::SURPREFIX );
		}
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::SURNAME ) ) !== false ) {
			$this->surname = parent::parse_text( $tree[$i1], Rp_Tags::SURNAME );
		}
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::SUFFIX ) ) !== false ) {
			$this->suffix = parent::parse_text( $tree[$i1], Rp_Tags::SUFFIX );
		}
		$off = 0;
		while ( ( $i1 = parent::find_tag( $tree, Rp_Tags::NOTE, $off ) ) !== false ) {
			$tmp = new RP_Note();
			$this->notes[] = $tmp->parse_tree( array( $tree[$i1] ), $ver );
			$off = $i1 + 1;
		}
		$off = 0;
		while ( ( $i1 = parent::find_tag( $tree, Rp_Tags::CITE, $off ) ) !== false ) {
			$tmp = new RP_Citation();
			$this->citations[] = $tmp->parse_tree( array( $tree[$i1] ), $ver );
			$off = $i1 + 1;
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
		$str = __CLASS__ . '(Version->' . $this->ver . ', Prefix->' . $this->prefix . ', Given->' . $this->given . ', NickName->' . $this->nick_name . ', SurnamePrefix->' . $this->surname_prefix . ', Surname->' . $this->surname . ', Suffix->' . $this->suffix;
		for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
			$str .= "\n" . $this->citations[$i];
		}
		for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
			$str .= "\n" . $this->notes[$i];
		}
		$str .= ')';
		return $str;
	}
}
