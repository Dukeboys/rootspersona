<?php
/**
 * \Genealogy\Gedcom\address
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   SVN: $Id: address.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * address class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Address extends RP_Entity_Abstract {
    /**
     * One to four line representation of a mailing address
     *  associated with the GEDCOM parent record
     *
     * @var string
     */
    var $address;
    /**
     * Break down of the mailing address into individual elements
     *
     * @var string
     */
    var $address_line1;
    var $address_line2;
    var $address_line3;
    var $city;
    var $state;
    var $postal_code;
    var $country;
    /**
     * Telephone Nbr  associated with the GEDCOM parent record
     *
     * @var string
     */
    var $phone;
    /**
     * Email address associated with the GEDCOM parent record
     *
     * @var string
     */
    var $email;
    /**
     * FAX Nbr associated with the GEDCOM parent record
     *
     * @var string
     */
    var $fax;
    /**
     * Website associated with the GEDCOM parent record
     *
     * @var unknown_type
     */
    var $www;
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
            if ( isset( $this->address )
            && $this->address != '' ) {
                $addr = explode( "\n", $this->address );
                $ged_rec .= $lvl . ' ' . Rp_Tags::ADDR . ' ' . $addr[0];
                $lvlplus = $lvl + 1;
                $cnt = count( $addr );
                for ( $i = 1;
	$i < $cnt;
	$i++ ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CONT . ' ' . $addr[$i];
                }
                if ( isset( $this->address_line1 )
                && $this->address_line1 != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::ADDR1 . ' ' . $this->address_line1;
                }
                if ( isset( $this->address_line2 )
                && $this->address_line2 != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::ADDR2 . ' ' . $this->address_line2;
                }
                if ( isset( $this->address_line3 )
                && $this->address_line3 != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::ADDR3 . ' ' . $this->address_line3;
                }
                if ( isset( $this->city )
                && $this->city != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CITY . ' ' . $this->city;
                }
                if ( isset( $this->state )
                && $this->state != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::STATE . ' ' . $this->state;
                }
                if ( isset( $this->postal_code )
                && $this->postal_code != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::POSTAL . ' ' . $this->postal_code;
                }
                if ( isset( $this->country )
                && $this->country != '' ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CTRY . ' ' . $this->country;
                }
            }
            if ( isset( $this->phone )
            && $this->phone != '' ) {
                if ( $ged_rec != '' ) {
                    $ged_rec .= "\n";
                }
                $ged_rec .= $lvl . ' ' . Rp_Tags::PHONE . ' ' . $this->phone;
            }
            if ( isset( $this->email )
            && $this->email != '' ) {
                if ( $ged_rec != '' ) {
                    $ged_rec .= "\n";
                }
                $ged_rec .= $lvl . ' ' . Rp_Tags::EMAIL . ' ' . $this->email;
            }
            if ( isset( $this->fax )
            && $this->fax != '' ) {
                if ( $ged_rec != '' ) {
                    $ged_rec .= "\n";
                }
                $ged_rec .= $lvl . ' ' . Rp_Tags::FAX . ' ' . $this->fax;
            }
            if ( isset( $this->www )
            && $this->www != '' ) {
                if ( $ged_rec != '' ) {
                    $ged_rec .= "\n";
                }
                $ged_rec .= $lvl . ' ' . Rp_Tags::WWW . ' ' . $this->www;
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
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::ADDR ) ) !== false ) {
            $this->address = parent::parse_con_tag( $tree[$i1], Rp_Tags::ADDR );
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::ADDR1 ) ) !== false ) {
                    $this->address_line1 = parent::parse_text( $sub2[$i2], Rp_Tags::ADDR1 );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::ADDR2 ) ) !== false ) {
                    $this->address_line2 = parent::parse_text( $sub2[$i2], Rp_Tags::ADDR2 );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::ADDR3 ) ) !== false ) {
                    $this->address_line3 = parent::parse_text( $sub2[$i2], Rp_Tags::ADDR3 );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::CITY ) ) !== false ) {
                    $this->city = parent::parse_text( $sub2[$i2], Rp_Tags::CITY );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::STATE ) ) !== false ) {
                    $this->state = parent::parse_text( $sub2[$i2], Rp_Tags::STATE );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::POSTAL ) ) !== false ) {
                    $this->postal_code = parent::parse_text( $sub2[$i2], Rp_Tags::POSTAL );
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::CTRY ) ) !== false ) {
                    $this->country = parent::parse_text( $sub2[$i2], Rp_Tags::CTRY );
                }
            }
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::PHONE ) ) !== false ) {
            $this->phone = parent::parse_text( $tree[$i1], Rp_Tags::PHONE );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::EMAIL ) ) !== false ) {
            $this->email = parent::parse_text( $tree[$i1], Rp_Tags::EMAIL );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::FAX ) ) !== false ) {
            $this->fax = parent::parse_text( $tree[$i1], Rp_Tags::FAX );
        }
        if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::WWW ) ) !== false ) {
            $this->www = parent::parse_text( $tree[$i1], Rp_Tags::WWW );
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
        return __CLASS__ . '(Version->' . $this->ver . ', Address->' . $this->address . ', AddressLine1->' . $this->address_line1 . ', AddressLine2->' . $this->address_line2 . ', AddressLine3->' . $this->address_line3 . ', City->' . $this->city . ', State->' . $this->state . ', PostalCode->' . $this->postal_code . ', Country->' . $this->country . ', Phone->' . $this->phone . ', Email->' . $this->email . ', FAX->' . $this->fax . ', WWW->' . $this->www . ')';
    }
}
