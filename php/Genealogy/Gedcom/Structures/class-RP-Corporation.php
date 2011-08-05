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
 * @version   SVN: $Id: Corporation.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RP_Corporation extends RP_Entity_Abstract {
    /**
     *
     * @var string
     */
    var $name;
    /**
     *
     * @var Address
     */
    var $address;
    /**
     * constructor
     */
    function __construct() {
        $this->address = new RP_Address();
    }
    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     * should be generated
     * @param string $ver represents the version of the GEDCOM standard
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function to_gedcom( $lvl, $ver ) {
        $ged_rec = '';
        if ( isset( $this->name )
        && $this->name != '' ) {
            $ged_rec .= $lvl . ' ' . Rp_Tags::CORP . ' ' . $this->name;
        }
        $lvl2 = $lvl + 1;
        if( isset( $this->address ) ) {
            $str = $this->address->to_gedcom( $lvl2, '5.5.1' );
        }
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
     * object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver ) {
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::CORP ) ) !== false ) {
            $this->name = parent::parse_text( $tree[$i1], Rp_Tags::CORP );
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                 if( isset( $this->address ) ) {
                    $this->address->parse_tree( $sub2, $ver );
                 }
            }
        }
    }
    /**
     * Creates a string representation of the object
     *
     * @return string  contains string representation of each
     * public field in the object
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function __toString() {
        $str = __CLASS__ . '(Name->' . $this->name . ', RP_Address->' . $this->address . ')';
        return $str;
    }
}
