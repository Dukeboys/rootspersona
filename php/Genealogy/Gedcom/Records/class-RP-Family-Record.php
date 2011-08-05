<?php
/**
 * \Genealogy\Gedcom\Records\FamilyRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: FamilyRecord.php 306 2010-04-13 22:16:26Z ed4becky $
 * @link
 */
/**
 * FamilyRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Family_Record extends RP_Record_Abstract {
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
    var $events = array();
    /**
     *
     * @var string
     */
    var $husband;
    /**
     *
     * @var string
     */
    var $wife;
    /**
     *
     * @var array
     */
    var $children = array();
    /**
     *
     * @var int
     */
    var $count_of_children;
    /**
     *
     * @var array
     */
    var $lds_sealings = array();
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
    public function to_gedcom( $lvl, $ver ) {
        $ged_rec = '';
        if ( ! isset( $ver )
        || $ver === '' ) {
            $ver = $this->ver;
        }
        if ( strpos( $ver, '5.5.1' ) == 0 ) {
            $ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::FAMILY;
            $lvl2 = $lvl + 1;
            if ( isset( $this->restriction )
            && $this->restriction != '' ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::RESTRICTION . ' ' . $this->restriction;
            }
            if ( isset( $this->husband )
            && $this->husband != '' ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::HUSBAND . ' @' . $this->husband . '@';
            }
            if ( isset( $this->wife )
            && $this->wife != '' ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::WIFE . ' @' . $this->wife . '@';
            }
            for ( $i = 0;
	$i < count( $this->children );
	$i++ ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::CHILD . ' @' . $this->children[$i] . '@';
            }
            if ( isset( $this->count_of_children )
            && $this->count_of_children != '' ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::CHILDCNT . ' ' . $this->count_of_children;
            }
            for ( $i = 0;
	$i < count( $this->events );
	$i++ ) {
                $ged_rec .= "\n" . $this->events[$i]->to_gedcom( $lvl2, $ver );
            }
            //            for ($i=0; $i<count($this->LdsSealings); $i++) {
            //                $gedRec .= "\n" . $this->LdsSealings[$i]->toGedcom($lvl2, $ver);
            //            }
            for ( $i = 0;
	$i < count( $this->submitter_links );
	$i++ ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::SUBMITTER . ' @' . $this->submitter_links[$i] . '@';
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
        $this->id = parent::parse_ref_id( $tree[0], Rp_Tags::FAMILY );
        if ( isset( $tree[0][1] ) ) {
            $sub2 = $tree[0][1];
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::RESTRICTION ) ) !== false ) {
                $this->restriction = parent::parse_text( $sub2[$i1], Rp_Tags::RESTRICTION );
            }
            $tmp = new RP_Event();
            $this->events = $tmp->parse_tree_to_array( $sub2, $ver );
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::HUSBAND ) ) !== false ) {
                $this->husband = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::HUSBAND );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::WIFE ) ) !== false ) {
                $this->wife = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::WIFE );
            }
            $off = 0;
            while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CHILD, $off ) ) !== false ) {
                $this->children[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::CHILD );
                $off = $i1 + 1;
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CHILDCNT ) ) !== false ) {
                $this->count_of_children = parent::parse_text( $sub2[$i1], Rp_Tags::CHILDCNT );
            }
            // TODO add support for LdsSealing
            //            $tmp = new RP_LdsSealing();
            //            $this->LdsSealings = $tmp->parseTreeToArray($sub2, $ver);
            $off = 0;
            while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SUBMITTER, $off ) ) !== false ) {
                $this->submitter_links[] = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::SUBMITTER );
                $off = $i1 + 1;
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
        $str = __CLASS__ . '(Id->' . $this->id . ', Restriction->' . $this->restriction . ', Events->(';
        for ( $i = 0;
	$i < count( $this->events );
	$i++ ) {
            $str .= "\n" . $this->events[$i];
        }
        $str .= '), Husband->' . $this->husband . ', Wife->' . $this->wife;
        for ( $i = 0;
	$i < count( $this->children );
	$i++ ) {
            $str .= ", Child->" . $this->children[$i];
        }
        $str .= ', CountOfChildren->' . $this->count_of_children;
        $str .= ', LdsSealings->(';
        for ( $i = 0;
	$i < count( $this->lds_sealings );
	$i++ ) {
            $str .= "\n" . $this->lds_sealings[$i];
        }
        $str .= '), UserRefNbrs->(';
        for ( $i = 0;
	$i < count( $this->user_ref_nbrs );
	$i++ ) {
            $str .= 'UserRefNbr->' . $this->user_ref_nbrs[$i]['Nbr'] . ' (' . $this->user_ref_nbrs[$i]['Type'] . ')';
        }
        $str .= '), AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', SubmitterLinks->(';
        for ( $i = 0;
	$i < count( $this->submitter_links );
	$i++ ) {
            if ( $i > 0 ) {
                $str .= ', ';
            }
            $str .= "Submitter->" . $this->submitter_links[$i];
        }
        $str .= '), Citations->(';
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
