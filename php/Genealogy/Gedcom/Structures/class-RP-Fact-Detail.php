<?php
/**
 * \Genealogy\Gedcom\FactDetail
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: FactDetail.php 296 2010-03-31 14:12:11Z ed4becky $
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
abstract class RP_Fact_Detail extends RP_Entity_Abstract {
    /**
     *
     * @var string
     */
    var $tag;
    /**
     *
     * @var string
     */
    var $descr;
    /**
     *
     * @var string
     */
    var $type;
    /**
     *
     * @var string
     */
    var $date;
    /**
     *
     * @var Place
     */
    var $place;
    /**
     *
     * @var address
     */
    var $address;
    /**
     *
     * @var string
     */
    var $age;
    /**
     *
     * @var string
     */
    var $resp_agency;
    /**
     *
     * @var string
     */
    var $religious_affiliation;
    /**
     *
     * @var string
     */
    var $restriction;
    /**
     *
     * @var string
     */
    var $cause;
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
     *
     * @var array
     */
    var $notes = array();
    /**
     * constructor
     */
    function __construct() {
        $this->place = new RP_Place();
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
        if ( ! isset( $ver )
        || $ver === '' ) {
            $ver = $this->ver;
        }
        $ged_rec = '';
        if ( strpos( $ver, '5.5.1' ) == 0 ) {
            if ( isset( $this->tag )
            && $this->tag != '' ) {
                $ged_rec .= $lvl . ' ' . $this->tag;
                if ( isset( $this->descr )
                && $this->descr != '' ) {
                    $ged_rec .= ' ' . $this->descr;
                }
                $lvl++;
                $ged_rec .= $this->to_gedcom_detail( $lvl, $ver );
            }
        }
        return $ged_rec;
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
    protected function to_gedcom_detail( $lvl, $ver ) {
        if ( ! isset( $ver )
        || $ver === '' ) {
            $ver = $this->ver;
        }
        $ged_rec = '';
        if ( strpos( $ver, '5.5.1' ) == 0 ) {
            if ( isset( $this->type )
            && $this->type != '' ) {
                $ged_rec .= "\n" . $lvl . ' ' . Rp_Tags::TYPE . ' ' . $this->type;
            }
            if ( isset( $this->date )
            && $this->date != '' ) {
                $ged_rec .= "\n" . $lvl . ' ' . Rp_Tags::DATE . ' ' . $this->date;
            }
            $str = $this->place->to_gedcom( $lvl, $ver );
            if ( isset( $str )
            && $str != '' ) {
                $ged_rec .= "\n" . $str;
            }
            $str = $this->address->to_gedcom( $lvl, $ver );
            if ( isset( $str )
            && $str != '' ) {
                $ged_rec .= "\n" . $str;
            }
            if ( isset( $this->age )
            && $this->age != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::AGE . ' ' . $this->age;
            }
            if ( isset( $this->resp_agency )
            && $this->resp_agency != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::AGENCY . ' ' . $this->resp_agency;
            }
            if ( isset( $this->religious_affiliation )
            && $this->religious_affiliation != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::RELIGION . ' ' . $this->religious_affiliation;
            }
            if ( isset( $this->restriction )
            && $this->restriction != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::RESTRICTION . ' ' . $this->restriction;
            }
            if ( isset( $this->cause )
            && $this->cause != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::CAUSE . ' ' . $this->cause;
            }
            for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
                $str .= "\n" . $this->citations[$i]->to_gedcom( $lvl, $ver );
            }
            for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
                $str .= "\n" . $this->media_links[$i]->to_gedcom( $lvl, $ver );
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
     * @param string $tag  tag of interest, deafulats to EVEN
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver, $tag = Rp_Tags::EVENT ) {
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, $tag ) ) !== false ) {
            $this->tag = $tag;
	$this->descr = parent::parse_text( $tree[$i1], $tag );
            $sub2 = $tree[$i1][1];
            $this->parse_tree_detail( $sub2, $ver );
        }
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
    protected function parse_tree_detail( $tree, $ver ) {
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::TYPE ) ) !== false ) {
            $this->type = parent::parse_text( $tree[$i1], Rp_Tags::TYPE );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::DATE ) ) !== false ) {
            $this->date = parent::parse_text( $tree[$i1], Rp_Tags::DATE );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::ADDRESS ) ) !== false ) {
            $this->address->parse_tree( array( $tree[$i1] ), $ver );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::PLACE ) ) !== false ) {
            $this->place->parse_tree( array( $tree[$i1] ), $ver );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::RELIGION ) ) !== false ) {
            $this->religious_affiliation = parent::parse_text( $tree[$i1], Rp_Tags::RELIGION );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::AGENCY ) ) !== false ) {
            $this->resp_agency = parent::parse_text( $tree[$i1], Rp_Tags::AGENCY );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::AGE ) ) !== false ) {
            $this->age = parent::parse_text( $tree[$i1], Rp_Tags::AGE );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::RESTRICTION ) ) !== false ) {
            $this->restriction = parent::parse_text( $tree[$i1], Rp_Tags::RESTRICTION );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::CAUSE ) ) !== false ) {
            $this->cause = parent::parse_text( $tree[$i1], Rp_Tags::CAUSE );
        }
        if( isset( $this->place ) ) {
            $this->place->parse_tree( $tree, $ver );
        }
        if( isset( $this->address ) ) {
            $this->address->parse_tree( $tree, $ver );
        }
        $off = 0;
        while ( ( $i1 = parent::find_tag( $tree, Rp_Tags::CITE, $off ) ) !== false ) {
            $tmp = new RP_Citation();
            $tmp->parse_tree( array( $tree[$i1] ), $ver );
            $this->citations[] = $tmp;
	$off = $i1 + 1;
        }
        $off = 0;
        while ( ( $i1 = parent::find_tag( $tree, Rp_Tags::MEDIA, $off ) ) !== false ) {
            $tmp = new RP_Media_Link();
            $tmp->parse_tree( array( $tree[$i1] ), $ver );
            $this->media_links[] = $tmp;
	$off = $i1 + 1;
        }
        $off = 0;
        while ( ( $i1 = parent::find_tag( $tree, Rp_Tags::NOTE, $off ) ) !== false ) {
            $tmp = new RP_Note();
            $tmp->parse_tree( array( $tree[$i1] ), $ver );
            $this->notes[] = $tmp;
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
        $str = __CLASS__ . '(Version->' . $this->ver . ', Tag->' . $this->tag . ', Description->' . $this->descr . ', Type->' . $this->type . ', Date->' . $this->date . ', Place->' . $this->place . ', RP_Address->' . $this->address . ', Age->' . $this->age . ', RespAgency->' . $this->resp_agency . ', ReligiousAffiliation->' . $this->religious_affiliation . ', Restriction->' . $this->restriction . ', Cause->' . $this->cause;
        for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
            $str .= "\n" . $this->citations[$i];
        }
        for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
            $str .= "\n" . $this->media_links[$i];
        }
        for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
            $str .= "\n" . $this->notes[$i];
        }
        $str .= ')';
        return $str;
    }
    /**
     * return an array of relevant child tags
     *
     * @return array
     */
    public abstract function get_types();
}
