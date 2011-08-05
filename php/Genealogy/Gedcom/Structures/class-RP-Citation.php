<?php
/**
 * \Genealogy\Gedcom\Citation
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Citation.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RP_Citation extends RP_Entity_Abstract {
	/**
	 *
	 * @var string
	 */
	var $source_id;
	/**
	 *
	 * @var string
	 */
	var $page;
	/**
	 *
	 * @var string
	 */
	var $event_type;
	/**
	 *
	 * @var string
	 */
	var $role_in_event;
	/**
	 * The date that this event data was entered
	 * into the original source document
	 *
	 * @var string
	 */
	var $entry_date;
	/**
	 * what the original record keeper said
	 *
	 * @var unknown_type
	 */
	var $texts = array();
	/**
	 *
	 * @var int
	 */
	var $quay;
	/**
	 *
	 * @var array
	 */
	var $media_links = array();
	/**
	 *
	 * @var array
	 */
	var $notes = array();
	// TODO support alternate (unpreferred) format
	
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
			if ( isset( $this->source_id )
			&& $this->source_id != '' ) {
				$ged_rec .= $lvl . ' ' . Rp_Tags::CITE . ' @' . $this->source_id . '@';
			}
			$lvl2 = $lvl + 1;
			if ( isset( $this->page )
			&& $this->page != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::PAGE . ' ' . $this->page;
			}
			if ( isset( $this->event_type )
			&& $this->event_type != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::EVENTTYPE . ' ' . $this->event_type;
				if ( isset( $this->rile_in_event )
				&& $this->role_in_event != '' ) {
					$ged_rec .= "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::ROLE . ' ' . $this->role_in_event;
				}
			}
			if ( isset( $this->entry_date )
			&& $this->entry_date != ''
			|| count( $this->texts ) > 0 ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DATA;
				$lvl3 = $lvl2 + 1;
				if ( isset( $this->entry_date )
				&& $this->entry_date != '' ) {
					$ged_rec .= "\n" . $lvl3 . ' ' . Rp_Tags::DATE . ' ' . $this->entry_date;
				}
				for ( $i = 0;
	$i < count( $this->texts );
	$i++ ) {
					$ged_rec .= "\n" . parent::to_con_tag( $this->texts[$i], Rp_Tags::TEXT, $lvl3 );
				}
			}
			if ( isset( $this->quay )
			&& $this->quay != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::QUAY . ' ' . $this->quay;
			}
			for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
				$ged_rec .= "\n" . $this->media_links[$i]->to_gedcom( $lvl2, $ver );
			}
			for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
				$ged_rec .= "\n" . $this->notes[$i]->to_gedcom( $lvl2, $ver );
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
		$this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::SOURCE ) ) !== false ) {
			$this->source_id = parent::parse_ptr_id( $tree[$i1], Rp_Tags::SOURCE );
			if ( isset( $tree[$i1][1] ) ) {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::PAGE ) ) !== false ) {
					$this->page = parent::parse_text( $sub2[$i2], Rp_Tags::PAGE );
				}
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::QUAY ) ) !== false ) {
					$this->quay = parent::parse_text( $sub2[$i2], Rp_Tags::QUAY );
				}
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::EVENTTYPE ) ) !== false ) {
					$this->event_type = parent::parse_text( $sub2[$i2], Rp_Tags::EVENTTYPE );
					if ( isset( $sub2[$i2][1] ) ) {
						if ( ( $i3 = parent::find_tag( $sub2[$i2][1], Rp_Tags::ROLE ) ) !== false ) {
							$this->role_in_event = parent::parse_text( $sub2[$i2][1][$i3], Rp_Tags::ROLE );
						}
					}
				}
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::DATA ) ) !== false ) {
					$sub3 = $sub2[$i2][1];
					if ( isset( $sub3 ) ) {
						if ( ( $i3 = parent::find_tag( $sub3, Rp_Tags::DATE ) ) !== false ) {
							$this->entry_date = parent::parse_text( $sub3[$i3], Rp_Tags::DATE );
						}
						$off = 0;
						while ( ( $i3 = parent::find_tag( $sub3, Rp_Tags::TEXT, $off ) ) !== false ) {
							$this->texts[] = parent::parse_con_tag( $sub3[$i3], Rp_Tags::TEXT );
							$off = $i3 + 1;
						}
					}
				}
				$off = 0;
				while ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::MEDIA, $off ) ) !== false ) {
					$tmp = new RP_Media_Link();
					$tmp->parse_tree( array( $sub2[$i2] ), $ver );
					$this->media_links[] = $tmp;
	$off = $i2 + 1;
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
		$str = __CLASS__ . '(Version->' . $this->ver . ', SourceId->' . $this->source_id . ', Page->' . $this->page . ', EventType->' . $this->event_type . ', RoleInEvent->' . $this->role_in_event . ', EntryDate->' . $this->entry_date . ', Texts->(';
		for ( $i = 0;
	$i < count( $this->texts );
	$i++ ) {
			$str .= "\nText->(" . $this->texts[$i] . ')';
		}
		$str .= '), Quay->' . $this->quay . ', MediaLinks->(';
		for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
			$str .= "\n" . $this->media_links[$i];
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
