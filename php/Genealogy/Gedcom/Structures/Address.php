<?php
/**
 * \Genealogy\Gedcom\Address
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   SVN: $Id: Address.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */



/**
 * Address class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */
class Address extends EntityAbstract
{
    /**
     * One to four line representation of a mailing address
     *  associated with the GEDCOM parent record
     *
     * @var string
     */
    var $Address;

    /**
     * Break down of the mailing address into individual elements
     *
     * @var string
     */
    var $AddressLine1;
    var $AddressLine2;
    var $AddressLine3;
    var $City;
    var $State;
    var $PostalCode;
    var $Country;

    /**
     * Telephone Nbr  associated with the GEDCOM parent record
     *
     * @var string
     */
    var $Phone;

    /**
     * Email address associated with the GEDCOM parent record
     *
     * @var string
     */
    var $Email;

    /**
     * FAX Nbr associated with the GEDCOM parent record
     *
     * @var string
     */
    var $FAX;

    /**
     * Website associated with the GEDCOM parent record
     *
     * @var unknown_type
     */
    var $WWW;

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
    public function toGedcom($lvl, $ver)
    {
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->Address) && $this->Address != '') {
                $addr =  explode("\n", $this->Address);
                $gedRec .= $lvl . ' ' . Tags::ADDR . ' ' . $addr[0];
                $lvlplus = $lvl + 1;
                for ($i=1;$i<count($addr);$i++) {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::CONT . ' ' .  $addr[$i];
                }
                if (isset($this->AddressLine1) && $this->AddressLine1 != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::ADDR1 . ' '
                    .  $this->AddressLine1;
                }
                if (isset($this->AddressLine2) && $this->AddressLine2 != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::ADDR2 . ' '
                    .  $this->AddressLine2;
                }
                if (isset($this->AddressLine3) && $this->AddressLine3 != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::ADDR3 . ' '
                    .  $this->AddressLine3;
                }
                if (isset($this->City) && $this->City != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::CITY
                    . ' ' .  $this->City;
                }
                if (isset($this->State) && $this->State != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::STATE . ' '
                    .  $this->State;
                }
                if (isset($this->PostalCode) && $this->PostalCode != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::POSTAL
                    . ' ' .  $this->PostalCode;
                }
                if (isset($this->Country) && $this->Country != '') {
                    $gedRec .= "\n" . $lvlplus . ' ' . Tags::CTRY . ' '
                    .  $this->Country;
                }
            }
            if (isset($this->Phone) && $this->Phone != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::PHONE . ' ' .  $this->Phone;
            }
            if (isset($this->Email) && $this->Email != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::EMAIL . ' ' .  $this->Email;
            }
            if (isset($this->FAX) && $this->FAX != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::FAX . ' ' .  $this->FAX;
            }
            if (isset($this->WWW) && $this->WWW != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::WWW . ' ' .  $this->WWW;
            }
        }
        return $gedRec;
    }

    /**
     * Extracts attribute contents from a parent tree object
     *
     * @param array  $tree an array containing an array from which the
     *                     object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     *                     data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        $this->Ver =$ver;
        if (($i1=parent::findTag($tree, Tags::ADDR))!==false) {
            $this->Address = parent::parseConTag($tree[$i1], Tags::ADDR);
            if (isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, Tags::ADDR1))!==false) {
                    $this->AddressLine1 = parent::parseText(
                        $sub2[$i2], Tags::ADDR1
                    );
                }
                if (($i2=parent::findTag($sub2, Tags::ADDR2))!==false) {
                    $this->AddressLine2 = parent::parseText(
                        $sub2[$i2], Tags::ADDR2
                    );
                }
                if (($i2=parent::findTag($sub2, Tags::ADDR3))!==false) {
                    $this->AddressLine3 = parent::parseText(
                        $sub2[$i2], Tags::ADDR3
                    );
                }
                if (($i2=parent::findTag($sub2, Tags::CITY))!==false) {
                    $this->City = parent::parseText($sub2[$i2], Tags::CITY);
                }
                if (($i2=parent::findTag($sub2, Tags::STATE))!==false) {
                    $this->State = parent::parseText($sub2[$i2], Tags::STATE);
                }
                if (($i2=parent::findTag($sub2, Tags::POSTAL))!==false) {
                    $this->PostalCode = parent::parseText(
                        $sub2[$i2], Tags::POSTAL
                    );
                }
                if (($i2=parent::findTag($sub2, Tags::CTRY))!==false) {
                    $this->Country = parent::parseText($sub2[$i2], Tags::CTRY);
                }
            }
        }
        if (($i1=parent::findTag($tree, Tags::PHONE))!==false) {
            $this->Phone = parent::parseText($tree[$i1], Tags::PHONE);
        }
        if (($i1=parent::findTag($tree, Tags::EMAIL))!==false) {
            $this->Email = parent::parseText($tree[$i1], Tags::EMAIL);
        }
        if (($i1=parent::findTag($tree, Tags::FAX))!==false) {
            $this->FAX = parent::parseText($tree[$i1], Tags::FAX);
        }
        if (($i1=parent::findTag($tree, Tags::WWW))!==false) {
            $this->WWW = parent::parseText($tree[$i1], Tags::WWW);
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
    public function __toString()
    {
        return __CLASS__
        . '(Version->' . $this->Ver
        . ', Address->' . $this->Address
        . ', AddressLine1->' . $this->AddressLine1
        . ', AddressLine2->' . $this->AddressLine2
        . ', AddressLine3->' . $this->AddressLine3
        . ', City->' . $this->City
        . ', State->' . $this->State
        . ', PostalCode->' . $this->PostalCode
        . ', Country->' . $this->Country
        . ', Phone->' . $this->Phone
        . ', Email->' . $this->Email
        . ', FAX->' . $this->FAX
        . ', WWW->' . $this->WWW
        . ')';
    }
}
