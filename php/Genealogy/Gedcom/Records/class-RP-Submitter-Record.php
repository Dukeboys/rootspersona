<?php
/**
 * \Genealogy\Gedcom\Records\SubmitterRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SubmitterRecord.php 297 2010-03-31 14:53:58Z ed4becky $
 * @link
 */
/**
 * SubmitterRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Submitter_Record extends RP_Record_Abstract {
    /**
     *
     * @var string
     */
    var $id;
    /**
     *
     * @var string
     */
    var $name;
    /**
     *
     * @var address
     */
    var $address;
    /**
     *
     * @var array
     */
    var $media_links = array();
    /**
     *
     * @var string
     */
    var $language;
    /**
     *
     * @var string
     */
    var $submitter_ref_nbr;
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
     * Initializes complex attributes
     *
     * @return none
     */
    public function __construct() {
        $this->change_date = new RP_Change_Date();
        $this->address = new RP_Address();
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
            $ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::SUBMITTER;
            $lvl2 = $lvl + 1;
            if ( isset( $this->name )
            && $this->name != '' ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::NAME . ' ' . $this->name;
            }
            $tmp = $this->address->to_gedcom( $lvl2, $ver );
            if ( isset( $tmp )
            && $tmp != '' ) {
                $ged_rec .= "\n" . $tmp;
            }
            if ( isset( $str )
            && $str != '' ) {
                $ged_rec .= "\n" . $str;
            }
            $ged_rec = $this->media_to_gedcom( $ged_rec, $lvl2, $ver );
            $ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $lvl2, $ver );
            $ged_rec = $this->change_date_to_gedcom( $ged_rec, $lvl2, $ver );
            $ged_rec = $this->note_to_gedcom( $ged_rec, $lvl2, $ver );
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
        $this->id = parent::parse_ref_id( $tree[0], Rp_Tags::SUBMITTER );
        if ( isset( $tree[0][1] ) ) {
            $sub2 = $tree[0][1];
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::NAME ) ) !== false ) {
                $this->name = parent::parse_text( $sub2[$i1], Rp_Tags::NAME );
            }
            $this->address->parse_tree( $sub2, $ver );
            $off = 0;
            while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::MEDIA, $off ) ) !== false ) {
                $tmp = new RP_Media_Link();
                $tmp->parse_tree( array( $sub2[$i1] ), $ver );
                $this->media_links[] = $tmp;
	$off = $i1 + 1;
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::LANGUAGE ) ) !== false ) {
                $this->language = parent::parse_text( $sub2[$i1], Rp_Tags::LANGUAGE );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::RFN ) ) !== false ) {
                $this->submitter_ref_nbr = parent::parse_text( $sub2[$i1], Rp_Tags::RFN );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::AUTORECID ) ) !== false ) {
                $this->auto_rec_id = parent::parse_text( $sub2[$i1], Rp_Tags::AUTORECID );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::CHANGEDATE ) ) !== false ) {
                $this->change_date->parse_tree( array( $sub2[$i1] ), $ver );
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
        $str = __CLASS__ . '(Id->' . $this->id . ', Name->' . $this->name . ', address->' . $this->address;
        for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
            $str .= "\n" . $this->media_links[$i];
        }
        $str .= ', Language->' . $this->language . ', SubmitterRefNbr->' . $this->submitter_ref_nbr . ', AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', Notes->(';
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
