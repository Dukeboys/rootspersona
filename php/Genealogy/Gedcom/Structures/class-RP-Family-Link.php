<?php
/**
 * \Genealogy\Gedcom\FamilyLink
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: FamilyLink.php 296 2010-03-31 14:12:11Z ed4becky $
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
class RP_Family_Link extends RP_Entity_Abstract {
    /**
     *
     * @var string
     */
    var $family_id;
    /**
     *
     * @var string
     */
    var $linkage_type;
    /**
     *
     * @var string
     */
    var $linkage_status;
    /**
     *
     * @var array
     */
    var $notes = array();
    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     *                    should be generated
     * @param string $ver represents the version of the GEDCOM standard
     * @param string $tag FAMS or FAMC
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function to_gedcom( $lvl, $ver, $tag = Rp_Tags::SPOUSEFAMILY ) {
        if ( ! isset( $ver )
        || $ver === '' ) {
            $ver = $this->ver;
        }
        $ged_rec = '';
        if ( strpos( $ver, '5.5.1' ) == 0 ) {
            if ( isset( $this->family_id )
            && $this->family_id != '' ) {
                $ged_rec .= $lvl . ' ' . $tag . ' @' . $this->family_id . '@';
                $lvl2 = $lvl + 1;
                if ( $tag == Rp_Tags::CHILDFAMILY ) {
                    if ( isset( $this->linkage_type )
                    && $this->linkage_type != '' ) {
                        $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::LINKTYPE . ' ' . $this->linkage_type;
                    }
                    if ( isset( $this->linkage_status )
                    && $this->linkage_status != '' ) {
                        $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::LINKSTATUS . ' ' . $this->linkage_status;
                    }
                }
                for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
                    $ged_rec .= "\n" . $this->notes[$i]->to_gedcom( $lvl2, $ver );
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
     * @param string $tag  FAMS or FAMC
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver, $tag = Rp_Tags::SPOUSEFAMILY ) {
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, $tag ) ) !== false ) {
            $this->family_id = parent::parse_ptr_id( $tree[$i1], $tag );
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                $off = 0;
                while ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::NOTE, $off ) ) !== false ) {
                    $tmp = new RP_Note();
                    $tmp->parse_tree( array( $sub2[$i2] ), $ver );
                    $this->notes[] = $tmp;
	$off = $i2 + 1;
                }
                if ( $tag == Rp_Tags::CHILDFAMILY ) {
                    if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::LINKTYPE ) ) !== false ) {
                        $this->linkage_type = parent::parse_text( $sub2[$i2], Rp_Tags::LINKTYPE );
                    }
                    if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::LINKSTATUS ) ) !== false ) {
                        $this->linkage_status = parent::parse_text( $sub2[$i2], Rp_Tags::LINKSTATUS );
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
        $str = __CLASS__ . '(FamilyId->' . $this->family_id;
        if ( isset( $this->linkage_type )
        && $this->linkage_type != '' ) {
            $str .= ', LinkageType->' . $this->linkage_type;
        }
        if ( isset( $this->linkage_status )
        && $this->linkage_status != '' ) {
            $str .= ', LinkageStatus->' . $this->linkage_status;
        }
        $str .= ', Notes->(';
        for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
            $str .= "\n" . $this->notes[$i];
        }
        $str .= '))';
        return $str;
    }
}
