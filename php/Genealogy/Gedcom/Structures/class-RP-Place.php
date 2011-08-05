<?php
/**
 * \Genealogy\Gedcom\Place
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Place.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RP_Place extends RP_Entity_Abstract {
    var $name;
	var $place_form;
	var $coordinates = array( 'Latitude' => '', 'Longitude' => '' );
    var $phonetic_names = array();
    var $romanized_names = array();
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
            if ( isset( $this->name )
            && $this->name != '' ) {
                $ged_rec .= $lvl . ' ' . Rp_Tags::PLACE . ' ' . $this->name;
                $lvl2 = $lvl + 1;
                if ( isset( $this->place_form )
                && $this->place_form != '' ) {
                    $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::FORM . ' ' . $this->place_form;
                }
                if ( isset( $this->coordinates['Latitude'] )
                && $this->coordinates['Latitude'] != '' ) {
                    $ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::MAP;
                    $ged_rec .= "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::LATITUDE . ' ' . $this->coordinates['Latitude'];
                    $ged_rec .= "\n" . ( $lvl2 + 1 ) . ' ' . Rp_Tags::LONGITUDE . ' ' . $this->coordinates['Longitude'];
                }
                for ( $i = 0;
	$i < count( $this->phonetic_names );
	$i++ ) {
                    $ged_rec .= "\n" . $this->phonetic_names[$i]->to_gedcom( $lvl2, $ver );
                }
                for ( $i = 0;
	$i < count( $this->romanized_names );
	$i++ ) {
                    $ged_rec .= "\n" . $this->romanized_names[$i]->to_gedcom( $lvl2, $ver );
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
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver ) {
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::PLACE ) ) !== false ) {
            $this->name = parent::parse_text( $tree[$i1], Rp_Tags::PLACE );
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::FORM ) ) !== false ) {
                    $this->place_form = parent::parse_text( $sub2[$i2], Rp_Tags::FORM );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::MAP ) ) !== false ) {
                    $sub3 = $sub2[$i2][1];
                    if ( ( $i3 = parent::find_tag( $sub3, Rp_Tags::LATITUDE ) ) !== false ) {
                        $this->coordinates['Latitude'] = parent::parse_text( $sub3[$i3], Rp_Tags::LATITUDE );
                    }
                    if ( ( $i3 = parent::find_tag( $sub3, Rp_Tags::LONGITUDE ) ) !== false ) {
                        $this->coordinates['Longitude'] = parent::parse_text( $sub3[$i3], Rp_Tags::LONGITUDE );
                    }
                }
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
                $off = 0;
                while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::NOTE, $off ) ) !== false ) {
                    $tmp = new RP_Note();
                    $tmp->parse_tree( array( $sub2[$i1] ), $ver );
                    $this->notes[] = $tmp;
	$off = $i1 + 1;
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
        $str = __CLASS__ . '(Version->' . $this->ver . ', Name->' . $this->name . ', PlaceForm->' . $this->place_form . ', Coordinates->' . $this->coordinates['Latitude'] . ' by ' . $this->coordinates['Longitude'];
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
        for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
            $str .= "\n" . $this->notes[$i];
        }
        $str .= ')';
        return $str;
    }
}
