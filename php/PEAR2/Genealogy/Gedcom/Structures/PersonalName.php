<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\PersonalName
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: PersonalName.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * PersonalName class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class PersonalName extends EntityAbstract
{
    /**
     *
     * @var Name
     */
    var $Name;
    /**
     *
     * @var array
     */
    var $PhoneticNames = array();
    /**
     *
     * @var array
     */
    var $RomanizedNames = array();

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->Name = new Name();
    }

    /**
     * Returns a concatenated string of a 'Full' name
     *
     * @return string The full name
     */
    public function getName() {
        return $this->Name->getFullName();
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
    public function toGedcom($lvl, $ver)
    {
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->Name) && $this->Name != '') {
                $gedRec .= $this->Name->toGedcom($lvl, $ver);
            }
            $lvl2 = $lvl + 1;
            for ($i=0; $i<count($this->PhoneticNames); $i++) {
                $str .= "\n" . $this->PhoneticNames[$i]->toGedcom($lvl2, $ver);
            }
            for ($i=0; $i<count($this->RomanizedNames); $i++) {
                $str .= "\n" . $this->RomanizedNames[$i]->toGedcom($lvl2, $ver);
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
        $this->Name->parseTree($tree, $ver);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::PHONETIC, $off))!==false) {
                $name = new Name();
                $name->parseTree(array($sub2[$i1]), $ver);
                $this->PhoneticNames[] = $name;
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::ROMANIZED, $off))!==false) {
                $name = new Name();
                $name->parseTree(array($sub2[$i1]), $ver);
                $this->RomanizedNames[] = $name;
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
    public function __toString()
    {
        $str = __CLASS__
        . '(Version->' . $this->Ver
        . ', Name->' . $this->Name;
        for ($i=0; $i<count($this->PhoneticNames); $i++) {
            $str .= "\n" . $this->PhoneticNames;
        }
        for ($i=0; $i<count($this->RomanizedNames); $i++) {
            $str .= "\n" . $this->RomanizedNames;
        }
        $str .= ')';

        return $str;
    }
}
