<?php
/**
 * \Genealogy\Gedcom\MediaFile
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: MediaFile.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * MediaFile class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Media_File extends RP_Entity_Abstract {
	var $ref_nbr;
	var $format;
	var $format_type;
	var $title;
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
			if ( isset( $this->ref_nbr )
			&& $this->ref_nbr != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::FILE . ' ' . $this->ref_nbr;
				$lvl2 = $lvl + 1;
				if ( isset( $this->format )
				&& $this->format != '' ) {
					$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::FORMAT . ' ' . $this->format;
					if ( isset( $this->format_type )
					&& $this->format_type != '' ) {
						$ged_rec .= "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::TYPE . ' ' . $this->format_type;
					}
				}
				if ( isset( $this->title )
				&& $this->title != '' ) {
					$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::TITLE . ' ' . $this->title;
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::FILE ) ) !== false ) {
			$this->ref_nbr = parent::parse_text( $tree[$i1], Rp_Tags::FILE );
			if ( isset( $tree[$i1][1] ) ) {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::FORMAT ) ) !== false ) {
					$this->format = parent::parse_text( $sub2[$i2], Rp_Tags::FORMAT );
					if ( isset( $sub2[$i2][1] ) ) {
						if ( ( $i3 = parent::find_tag( $sub2[$i2][1], Rp_Tags::TYPE ) ) !== false ) {
							$this->format_type = parent::parse_text( $sub2[$i2][1][$i3], Rp_Tags::TYPE );
						}
					}
				}
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::TITLE ) ) !== false ) {
					$this->title = parent::parse_text( $sub2[$i2], Rp_Tags::TITLE );
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
		$str = __CLASS__ . '(RefNbr->' . $this->ref_nbr . ', Format->' . $this->format . ', FormatType->' . $this->format_type . ', Title->' . $this->title . ')';
		return $str;
	}
}
