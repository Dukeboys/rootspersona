<?php
/**
 * \Genealogy\Gedcom\Records\IndividualRecord
 *
 * PHP version 5
 *
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: IndividualRecord.php 306 2010-04-13 22:16:26Z ed4becky $
 * @link
 */
/**
 * IndividualRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Individual_Record extends RP_Record_Abstract {
	/**
	 *
	 * @var string
	 */
	var $id;
	/**
	 *
	 * @var string
	 */
	var $restriction;
	/**
	 *
	 * @var array
	 */
	var $names = array();
	/**
	 *
	 * @var string
	 */
	var $gender;
	/**
	 *
	 * @var array
	 */
	var $events;
	/**
	 *
	 * @var array
	 */
	var $attributes;
	/**
	 *
	 * @var array
	 */
	var $lds_ordinances;
	/**
	 *
	 * @var array
	 */
	var $child_family_links = array();
	/**
	 *
	 * @var array
	 */
	var $spouse_family_links = array();
	/**
	 *
	 * @var array
	 */
	var $submitter_links = array();
	/**
	 *
	 * @var array
	 */
	var $associations = array();
	/**
	 *
	 * @var array
	 */
	var $aliases = array();
	/**
	 *
	 * @var array
	 */
	var $ancestor_interests = array();
	/**
	 *
	 * @var array
	 */
	var $descendant_interests = array();
	/**
	 *
	 * @var string
	 */
	var $perm_rec_file_nbr;
	/**
	 *
	 * @var string
	 */
	var $anc_file_nbr;
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
	var $notes = array();
	/**
	 *
	 * @var array
	 */
	var $citations = array();
	/**
	 *
	 * @var array
	 */
	var $media_links = array();
	/**
	 * Initializes complex attributes
	 *
	 * @return none
	 */
	public function __construct() {
		$this->change_date = new RP_Change_Date();
	}
	/**
	 * Returns the default name
	 *
	 * @return string default (first) Full name
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function get_full_name() {
		if ( isset( $this->names[0] )
		&& isset( $this->names[0]->rp_name ) )return $this->names[0]->rp_name->get_full_name();
		else return null;
	}
	/**
	 * Returns the default surname
	 *
	 * @return string default (first) surname
	 * @access public
	 * @since Method available since Release 0.0.1
	 */
	public function get_surname() {
		if ( isset( $this->names[0] )
		&& isset( $this->names[0]->rp_name ) )return $this->names[0]->rp_name->get_surname();
		else return null;
	}
	

	/**
	 * @todo Description of function getGiven
	 * @param 
	 * @return 
	 */
	public function get_given() {
		if ( isset( $this->names[0] )
		&& isset( $this->names[0]->rp_name ) )return $this->names[0]->rp_name->get_given();
		else return null;
	}
	/**
	 * Returns the nth instance of a specific event type
	 *
	 * @param string $tag    event type of interest
	 * @param int    $offset instance nbr
	 */
	public function get_event( $tag, $offset = 1 ) {
		$events = $this->events;
		$idx = 1;
		foreach ( $events as $event ) {
			if ( $event->tag === $tag
			|| ( $event->tag === 'EVEN'
			&& $event->type === $tag ) ) {
				if ( $offset == $idx ) {
					return $event;
				}
				$idx++;
			}
		}
		return false;
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
	public function to_gedcom( $lvl = 0, $ver = '' ) {
		$ged_rec = '';
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			$ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::INDIVIDUAL;
			$lvl2 = $lvl + 1;
			if ( isset( $this->restriction )
			&& $this->restriction != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::RESTRICTION . ' ' . $this->restriction;
			}
			for ( $i = 0;
	$i < count( $this->names );
	$i++ ) {
				$ged_rec .= "\n" . $this->names[$i]->to_gedcom( $lvl2, $ver );
			}
			if ( isset( $this->gender )
			&& $this->gender != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::GENDER . ' ' . $this->gender;
			}
			for ( $i = 0;
	$i < count( $this->events );
	$i++ ) {
				$ged_rec .= "\n" . $this->events[$i]->to_gedcom( $lvl2, $ver );
			}
			for ( $i = 0;
	$i < count( $this->attributes );
	$i++ ) {
				$ged_rec .= "\n" . $this->attributes[$i]->to_gedcom( $lvl2, $ver );
			}
			//  for ($i=0; $i<count($this->LdsOrdinances); $i++) {
			//     $str .= "\n" . $this->LdsOrdinances[$i]->toGedcom($lvl2, $ver);
			//  }
			for ( $i = 0;
	$i < count( $this->child_family_links );
	$i++ ) {
				$ged_rec .= "\n" . $this->child_family_links[$i]->to_gedcom( $lvl2, $ver, Rp_Tags::CHILDFAMILY );
			}
			for ( $i = 0;
	$i < count( $this->spouse_family_links );
	$i++ ) {
				$ged_rec .= "\n" . $this->spouse_family_links[$i]->to_gedcom( $lvl2, $ver, Rp_Tags::SPOUSEFAMILY );
			}
			for ( $i = 0;
	$i < count( $this->submitter_links );
	$i++ ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::SUBMITTER . ' @' . $this->submitter_links[$i] . '@';
			}
			for ( $i = 0;
	$i < count( $this->associations );
	$i++ ) {
				$ged_rec .= "\n" . $this->associations[$i]->to_gedcom( $lvl2, $ver );
			}
			for ( $i = 0;
	$i < count( $this->aliases );
	$i++ ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::ALIAS . ' @' . $this->aliases[$i] . '@';
			}
			for ( $i = 0;
	$i < count( $this->ancestor_interests );
	$i++ ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::ANCI . ' @' . $this->ancestor_interests[$i] . '@';
			}
			for ( $i = 0;
	$i < count( $this->descendant_interests );
	$i++ ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DESI . ' @' . $this->descendant_interests[$i] . '@';
			}
			if ( isset( $this->perm_rec_file_nbr )
			&& $this->perm_rec_file_nbr != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::PERMFILE . ' ' . $this->perm_rec_file_nbr;
			}
			if ( isset( $this->anc_file_nbr )
			&& $this->anc_file_nbr != '' ) {
				$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::ANCFILE . ' ' . $this->anc_file_nbr;
			}
			$ged_rec = $this->common_to_gedcom( $ged_rec, $lvl2, $ver );
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
		$this->ver = $ver;
	$this->id = parent::parse_ref_id( $tree[0], Rp_Tags::INDIVIDUAL );
		if ( isset( $tree[0][1] ) ) {
			$sub2 = $tree[0][1];
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::RESTRICTION ) ) !== false ) {
				$this->restriction = parent::parse_text( $sub2[$i1], Rp_Tags::RESTRICTION );
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::FULL, $off ) ) !== false ) {
				$tmp = new RP_Personal_Name();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->names[] = $tmp;
	$off = $i1 + 1;
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::GENDER ) ) !== false ) {
				$this->gender = parent::parse_text( $sub2[$i1], Rp_Tags::GENDER );
			}
			$tmp = new RP_Event();
			$this->events = $tmp->parse_tree_to_array( $sub2, $ver );
			$tmp = new RP_Fact();
			$this->attributes = $tmp->parse_tree_to_array( $sub2, $ver );
			//TODO add support for LdsOrdinances
			//$this->LdsOrdinances = LdsOrdinance::parseTreeToArray($sub2, $ver);
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CHILDFAMILY, $off ) ) !== false ) {
				$tmp = new RP_Family_Link();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver, Rp_Tags::CHILDFAMILY );
				$this->child_family_links[] = $tmp;
	$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SPOUSEFAMILY, $off ) ) !== false ) {
				$tmp = new RP_Family_Link();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver, Rp_Tags::SPOUSEFAMILY );
				$this->spouse_family_links[] = $tmp;
	$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SUBMITTER, $off ) ) !== false ) {
				$this->submitter_links[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::SUBMITTER );
				$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ASSOCIATION, $off ) ) !== false ) {
				$tmp = new RP_Association();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->associations[] = $tmp;
	$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ALIAS, $off ) ) !== false ) {
				$this->aliases[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::ALIAS );
				$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ANCI, $off ) ) !== false ) {
				$this->ancestor_interests[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::ANCI );
				$off = $i1 + 1;
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::DESI, $off ) ) !== false ) {
				$this->descendant_interests[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::DESI );
				$off = $i1 + 1;
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::PERMFILE ) ) !== false ) {
				$this->perm_rec_file_nbr = parent::parse_text( $sub2[$i1], Rp_Tags::PERMFILE );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ANCFILE ) ) !== false ) {
				$this->anc_file_nbr = parent::parse_text( $sub2[$i1], Rp_Tags::ANCFILE );
			}
			$this->common_parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(Id->' . $this->id . ', Restriction->' . $this->restriction . ', Names->(';
		for ( $i = 0;
	$i < count( $this->names );
	$i++ ) {
			$str .= "\n" . $this->names[$i];
		}
		$str .= '), Gender->' . $this->gender . ', Events->(';
		for ( $i = 0;
	$i < count( $this->events );
	$i++ ) {
			$str .= "\n" . $this->events[$i];
		}
		$str .= '), Attributes->(';
		for ( $i = 0;
	$i < count( $this->attributes );
	$i++ ) {
			$str .= "\n" . $this->attributes[$i];
		}
		$str .= '), LdsOrdinances->(';
		for ( $i = 0;
	$i < count( $this->lds_ordinances );
	$i++ ) {
			$str .= "\n" . $this->lds_ordinances[$i];
		}
		$str .= '), ChildFamilyLinks->(';
		for ( $i = 0;
	$i < count( $this->child_family_links );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->child_family_links[$i];
		}
		$str .= '), SpouseFamilyLinks->(';
		for ( $i = 0;
	$i < count( $this->spouse_family_links );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->spouse_family_links[$i];
		}
		$str .= '), SubmitterLinks->(';
		for ( $i = 0;
	$i < count( $this->submitter_links );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->submitter_links[$i];
		}
		$str .= '), Associations->(';
		for ( $i = 0;
	$i < count( $this->associations );
	$i++ ) {
			$str .= "\n" . $this->associations[$i];
		}
		$str .= '), Aliases->(';
		for ( $i = 0;
	$i < count( $this->aliases );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->aliases[$i];
		}
		$str .= '), AncestorInterests->(';
		for ( $i = 0;
	$i < count( $this->ancestor_interests );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->ancestor_interests[$i];
		}
		$str .= '), DescendantInterests->(';
		for ( $i = 0;
	$i < count( $this->descendant_interests );
	$i++ ) {
			if ( $i > 0 ) {
				$str .= ', ';
			}
			$str .= $this->descendant_interests[$i];
		}
		$str .= '), PermRecFileNbr->' . $this->perm_rec_file_nbr . ', AncFileNbr->' . $this->anc_file_nbr;
		$str .= ', UserRefNbrs->(';
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
		$str .= '), MediaLinks->(';
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
