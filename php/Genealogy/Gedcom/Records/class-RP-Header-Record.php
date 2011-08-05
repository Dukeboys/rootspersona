<?php
/**
 * \Genealogy\Gedcom\Records\HeaderRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: HeaderRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * HeaderRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Header_Record extends RP_Entity_Abstract {
    /**
     *
     * @var SourceSystem
     */
    var $source_system;
    /**
     *
     * @var string
     */
    var $destination_system;
    /**
     *
     * @var string
     */
    var $transmission_date_time;
    /**
     *
     * @var string
     */
    var $submitter_id;
    /**
     *
     * @var string
     */
    var $submission_id;
    /**
     *
     * @var string
     */
    var $filename;
    /**
     *
     * @var string
     */
    var $copyright;
    /**
     *
     * @var string
     */
    var $language;
    /**
     *
     * @var CharacterSet
     */
    var $character_set;
    /**
     *
     * @var GedC
     */
    var $ged_c;
    /**
     *
     * @var string
     */
    var $place_form;
    /**
     *
     * @var Note
     */
    var $note;
    /**
     * Initializes complex attributes
     *
     * @return none
     */
    public function __construct() {
        $this->source_system = new RP_Source_System();
        $this->character_set = new RP_Character_Set();
        $this->ged_c = new RP_Ged_C();
        $this->note = new RP_Note();
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
        $ged_rec = $lvl . ' ' . Rp_Tags::HEADER;
        $lvl2 = $lvl + 1;
        $ged_rec .= "\n" . $this->source_system->to_gedcom( $lvl2, $ver );
        if ( isset( $this->destination_system )
        && $this->destination_system != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DEST . ' ' . $this->destination_system;
        }
        if ( isset( $this->transmission_date_time )
        && $this->transmission_date_time != '' ) {
            $d = explode( ' ', $this->transmission_date_time );
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::DATE . ' ' . $d[0];
            if ( isset( $d[1] ) ) {
                $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::TIME . ' ' . $d[1];
            }
        }
        if ( isset( $this->submitter_id )
        && $this->submitter_id != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::SUBMITTER . ' @' . $this->submitter_id . '@';
        }
        if ( isset( $this->submission_id )
        && $this->submission_id != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::SUBMISSION . ' @' . $this->submission_id . '@';
        }
        if ( isset( $this->filename )
        && $this->filename != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::FILE . ' ' . $this->filename;
        }
        if ( isset( $this->copyright )
        && $this->copyright != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::COPYRIGHT . ' ' . $this->copyright;
        }
        $str = $this->ged_c->to_gedcom( $lvl2, $ver );
        if ( isset( $str )
        && $str != ''
        && strpos( $str, Rp_Tags::VERSION ) !== false ) {
            $ged_rec .= "\n" . $str;
        }
        $str = $this->character_set->to_gedcom( $lvl2, $ver );
        if ( isset( $str )
        && $str != '' ) {
            $ged_rec .= "\n" . $str;
        }
        if ( isset( $this->language )
        && $this->language != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::LANGUAGE . ' ' . $this->language;
        }
        if ( isset( $this->place_form )
        && $this->place_form != '' ) {
            $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::PLACE . "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::FORM . ' ' . $this->place_form;
        }
        $str = $this->note->to_gedcom( $lvl2, $ver );
        if ( isset( $str )
        && $str != '' ) {
            $ged_rec .= "\n" . $str;
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
        if ( isset( $tree[0][1] ) ) {
            $sub2 = $tree[0][1];
            $this->ged_c->parse_tree( $sub2, $ver );
            if ( empty( $ver ) )$ver = $this->ged_c->ver;
            $this->source_system->parse_tree( $sub2, $ver );
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::DEST ) ) !== false ) {
                $this->destination_system = parent::parse_text( $sub2[$i1], Rp_Tags::DEST );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::DATE ) ) !== false ) {
                $this->transmission_date_time = parent::parse_text( $sub2[$i1], Rp_Tags::DATE );
                if ( isset( $sub2[$i1][1] ) ) {
                    if ( ( $i2 = parent::find_tag( $sub2[$i1][1], Rp_Tags::TIME ) ) !== false ) {
                        $this->transmission_date_time .= ' ' . parent::parse_text( $sub2[$i1][1][$i2], Rp_Tags::TIME );
                    }
                }
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::FILE ) ) !== false ) {
                $this->filename = parent::parse_text( $sub2[$i1], Rp_Tags::FILE );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::COPYRIGHT ) ) !== false ) {
                $this->copyright = parent::parse_text( $sub2[$i1], Rp_Tags::COPYRIGHT );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::LANGUAGE ) ) !== false ) {
                $this->language = parent::parse_text( $sub2[$i1], Rp_Tags::LANGUAGE );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SUBMITTER ) ) !== false ) {
                $this->submitter_id = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::SUBMITTER );
            }
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::SUBMISSION ) ) !== false ) {
                $this->submission_id = parent::parse_ptr_id( $sub2[$i1], Rp_Tags::SUBMISSION );
            }
            $this->character_set->parse_tree( $sub2, $ver );
            $this->note->parse_tree( $sub2, $ver );
            if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::PLACE ) ) !== false ) {
                if ( isset( $sub2[$i1][1] ) ) {
                    if ( ( $i2 = parent::find_tag( $sub2[$i1][1], Rp_Tags::FORM ) ) !== false ) {
                        $this->place_form = parent::parse_text( $sub2[$i1][1][$i2], Rp_Tags::FORM );
                    }
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
        $str = __CLASS__ . '(SourceSystem->' . $this->source_system . ', DestinationSystem->' . $this->destination_system . ', TransmissionDateTime->' . $this->transmission_date_time . ', SubmitterId->' . $this->submitter_id . ', SubmissionId->' . $this->submission_id . ', Filename->' . $this->filename . ', Copyright->' . $this->copyright . ', Language->' . $this->language . ', CharacterSet->' . $this->character_set . ', GedC->' . $this->ged_c . ', PlaceForm->' . $this->place_form . ', Note->' . $this->note . ')';
        return $str;
    }
}
?>
