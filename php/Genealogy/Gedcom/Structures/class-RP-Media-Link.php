<?php
/**
 * \Genealogy\Gedcom\MediaLink
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: MediaLink.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RP_Media_Link extends RP_Entity_Abstract {
	var $id;
	var $title; // MediaLink and Mediarecord treat Title differently per spec
	var $media_files = array();
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
			if ( isset( $this->id )
			&& $this->id != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::MEDIA . ' @' . $this->id . '@';
			} else {
				$ged_rec .= $lvl . ' ' . Rp_Tags::MEDIA;
				$lvl2 = $lvl + 1;
				if ( isset( $this->title )
				&& $this->title != '' ) {
					$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::TITLE . ' ' . $this->title;
				}
				for ( $i = 0;
	$i < count( $this->media_files );
	$i++ ) {
					$ged_rec .= "\n" . $this->media_files[$i]->to_gedcom( $lvl2, $ver );
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::MEDIA ) ) !== false ) {
			$str = parent::parse_ptr_id( $tree[$i1], Rp_Tags::MEDIA );
			if ( isset( $str )
			&& $str != '' ) {
				$this->id = $str;
			} else {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::TITLE ) ) !== false ) {
					$this->title = parent::parse_text( $sub2[$i2], Rp_Tags::TITLE );
				}
				$off = 0;
				while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::FILE, $off ) ) !== false ) {
					$tmp = new RP_Media_File();
					$tmp->parse_tree( array( $sub2[$i1] ), $ver );
					$this->media_files[] = $tmp;
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
		$str = __CLASS__ . '(Version->' . $this->ver;
		if ( isset( $this->id )
		&& $this->id != '' ) {
			$str .= ', Id->' . $this->id;
		} else {
			$str .= ', Title->' . $this->title . ', MediaFiles->(';
			for ( $i = 0;
	$i < count( $this->media_files );
	$i++ ) {
				$str .= "\n" . $this->media_files[$i];
			}
			$str .= ')';
		}
		$str .= ')';
		return $str;
	}
}
?>
