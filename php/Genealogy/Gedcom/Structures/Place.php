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
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */



/**
 * Citation class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */
class Place extends EntityAbstract
{
    var $Name;
    var $PlaceForm;
    var $Coordinates = array('Latitude' => '',
                     'Longitude' => '');
    var $PhoneticNames = array();
    var $RomanizedNames = array();
    var $Notes = array();
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
            if (isset($this->Name) && $this->Name != '') {
                $gedRec .= $lvl . ' ' . rpTags::PLACE . ' ' . $this->Name;

                $lvl2 = $lvl+1;
                if (isset($this->PlaceForm) && $this->PlaceForm != '') {
                    $gedRec .= "\n" . $lvl2
                        . ' ' . rpTags::FORM . ' ' . $this->PlaceForm;
                }
                if (isset($this->Coordinates['Latitude'])
                    && $this->Coordinates['Latitude']!= ''
                ) {
                    $gedRec .= "\n" . $lvl2 . ' ' . rpTags::MAP;
                    $gedRec .= "\n" .($lvl2+1)
                        . ' ' . rpTags::LATITUDE
                        . ' ' . $this->Coordinates['Latitude'];
                    $gedRec .= "\n" .($lvl2+1)
                        . ' ' . rpTags::LONGITUDE
                        . ' ' . $this->Coordinates['Longitude'];
                }
                for ($i=0; $i<count($this->PhoneticNames); $i++) {
                    $gedRec .= "\n"
                        . $this->PhoneticNames[$i]->toGedcom($lvl2, $ver);
                }
                for ($i=0; $i<count($this->RomanizedNames); $i++) {
                    $gedRec .= "\n"
                        . $this->RomanizedNames[$i]->toGedcom($lvl2, $ver);
                }
                for ($i=0; $i<count($this->Notes); $i++) {
                    $gedRec .= "\n" . $this->Notes[$i]->toGedcom($lvl2, $ver);
                }
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
        if (($i1=parent::findTag($tree, rpTags::PLACE))!==false) {
            $this->Name = parent::parseText($tree[$i1], rpTags::PLACE);
            if (isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, rpTags::FORM))!==false) {
                    $this->PlaceForm = parent::parseText($sub2[$i2], rpTags::FORM);
                }
                if (($i2=parent::findTag($sub2, rpTags::MAP))!==false) {
                    $sub3 = $sub2[$i2][1];
                    if (($i3=parent::findTag($sub3, rpTags::LATITUDE))!==false) {
                        $this->Coordinates['Latitude']
                            = parent::parseText($sub3[$i3], rpTags::LATITUDE);

                    }
                    if (($i3=parent::findTag($sub3, rpTags::LONGITUDE))!==false) {
                        $this->Coordinates['Longitude']
                            = parent::parseText($sub3[$i3], rpTags::LONGITUDE);
                    }
                }
                $off = 0;
                while (($i1=parent::findTag($sub2, rpTags::PHONETIC, $off))!==false) {
                    $name = new rpName();
                    $name->parseTree(array($sub2[$i1]), $ver);
                    $this->PhoneticNames[] = $name;
                    $off = $i1 + 1;
                }
                $off = 0;
                while (($i1=parent::findTag($sub2, rpTags::ROMANIZED, $off))!==false) {
                    $name = new rpName();
                    $name->parseTree(array($sub2[$i1]), $ver);
                    $this->RomanizedNames[] = $name;
                    $off = $i1 + 1;
                }
                $off = 0;
                while (($i1=parent::findTag($sub2, rpTags::NOTE, $off))!==false) {
                    $tmp = new Note();
                    $tmp->parseTree(array($sub2[$i1]), $ver);
                    $this->Notes[] = $tmp;
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
    public function __toString()
    {
        $str = __CLASS__ . '(Version->' . $this->Ver
        . ', Name->' . $this->Name
        . ', PlaceForm->' . $this->PlaceForm
        . ', Coordinates->' . $this->Coordinates['Latitude']
        . ' by ' . $this->Coordinates['Longitude'];
        for ($i=0; $i<count($this->PhoneticNames); $i++) {
            $str .= "\n" . $this->PhoneticNames;
        }
        for ($i=0; $i<count($this->RomanizedNames); $i++) {
            $str .= "\n" . $this->RomanizedNames;
        }
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }
        $str .= ')';
        return $str;
    }
}
