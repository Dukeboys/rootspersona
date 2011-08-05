<?php
/**
 * \Genealogy\Gedcom\rpData
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: rpData.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * rpData class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Data extends RP_Entity_Abstract {
    /**
     *
     * @var string
     */
    var $source_name;
    /**
     *
     * @var string
     */
    var $date;
    /**
     *
     * @var string
     */
    var $copyright;
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
        if ( isset( $this->source_name )
        && $this->source_name != '' ) {
            $ged_rec .= $lvl . ' ' . Rp_Tags::DATA . ' ' . $this->source_name;
        }
        $lvl2 = $lvl + 1;
        if ( isset( $this->date )
        && $this->date != '' ) {
            if ( $ged_rec != '' ) {
                $ged_rec .= "\n";
            }
            $ged_rec .= $lvl2 . ' ' . Rp_Tags::DATE . ' ' . $this->date;
        }
        if ( isset( $this->copyright )
        && $this->copyright != '' ) {
            $ged_rec .= "\n" . parent::to_con_tag( $this->copyright, Rp_Tags::COPYRIGHT, $lvl2 );
        }
        return $ged_rec;
    }
    /**
     * Extracts attribute contents FROM a parent tree object
     *
     * @param array  $tree an array containing an array FROM which the
     * object rpData should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * rpData is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver ) {
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::DATA ) ) !== false ) {
            $this->source_name = parent::parse_text( $tree[$i1], Rp_Tags::DATA );
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::DATE ) ) !== false ) {
                    $this->date .= parent::parse_text( $sub2[$i2], Rp_Tags::DATE );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::COPYRIGHT ) ) !== false ) {
                    $this->copyright .= parent::parse_con_tag( $sub2[$i2], Rp_Tags::COPYRIGHT );
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
        $str = __CLASS__ . '(SourceName->' . $this->source_name . ', Date->' . $this->date . ', Copyright->' . $this->copyright . ')';
        return $str;
    }
}
