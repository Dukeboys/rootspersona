<?php
/**
 * \Genealogy\Gedcom\PersonalName
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: PersonalName.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link
 */
/**
 * PersonalName class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Personal_Name extends RP_Entity_Abstract {
    /**
     *
     * @var Name
     */
    var $rp_name;
    /**
     *
     * @var array
     */
    var $phonetic_names = array();
    /**
     *
     * @var array
     */
    var $romanized_names = array();
    /**
     *  constructor
     */
    public function __construct() {
        $this->rp_name = new RP_Name();
    }
    /**
     * Returns a concatenated string of a 'Full' name
     *
     * @return string The full name
     */
    public function get_name() {
        return $this->rp_name->get_full_name();
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
            if ( isset( $this->rp_name )
            && $this->rp_name != '' ) {
                $ged_rec .= $this->rp_name->to_gedcom( $lvl, $ver );
            }
            $lvl2 = $lvl + 1;
            for ( $i = 0;
	$i < count( $this->phonetic_names );
	$i++ ) {
                $str .= "\n" . $this->phonetic_names[$i]->to_gedcom( $lvl2, $ver );
            }
            for ( $i = 0;
	$i < count( $this->romanized_names );
	$i++ ) {
                $str .= "\n" . $this->romanized_names[$i]->to_gedcom( $lvl2, $ver );
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
        $this->ver = $ver;
	$this->rp_name->parse_tree( $tree, $ver );
        if ( isset( $tree[0][1] ) ) {
            $sub2 = $tree[0][1];
            $off = 0;
            while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::PHONETIC, $off ) ) !== false ) {
                $name = new RP_Name();
                $name->parse_tree( array( $sub2[$i1] ), $ver );
                $this->phonetic_names[] = $name;
	$off = $i1 + 1;
            }
            $off = 0;
            while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ROMANIZED, $off ) ) !== false ) {
                $name = new RP_Name();
                $name->parse_tree( array( $sub2[$i1] ), $ver );
                $this->romanized_names[] = $name;
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
        $str = __CLASS__ . '(Version->' . $this->ver . ', rpName->' . $this->rp_name;
        for ( $i = 0;
	$i < count( $this->phonetic_names );
	$i++ ) {
            $str .= "\n" . $this->phonetic_names;
        }
        for ( $i = 0;
	$i < count( $this->romanized_names );
	$i++ ) {
            $str .= "\n" . $this->romanized_names;
        }
        $str .= ')';
        return $str;
    }
}
