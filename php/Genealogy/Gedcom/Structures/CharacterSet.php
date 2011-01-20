<?php
/**
 * \Genealogy\Gedcom\CharacterSet
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: CharacterSet.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */




/**
 * CharacterSet class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */

class CharacterSet extends EntityAbstract
{

    /**
     * ANSEL |UTF-8 | UNICODE | ASCII
     *
     * @var string
     */
    var $CharacterSet;

    /**
     * An identifier that represents the version level assigned
     * to the associated product. It is defined and changed by
     * the creators of the product.
     *
     * @var string
     */
    var $VerNbr;

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
    public function toGedcom($lvl, $ver)
    {
        $gedRec = '';

        if (isset($this->CharacterSet) && $this->CharacterSet != '') {
            $gedRec .= $lvl . ' ' . Tags::CHAR . ' ' . $this->CharacterSet;
        }
        $lvl2 = $lvl + 1;
        if (isset($this->VerNbr) && $this->VerNbr != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::VERSION . ' ' . $this->VerNbr;
        }

        return $gedRec;
    }

    /**
     * Extracts attribute contents from a parent tree object
     *
     * @param array  $tree an array containing an array from which the
     * object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        $this->Ver = $ver;
        if (($i1 = parent::findTag($tree, Tags::CHAR)) !== false) {
            $this->CharacterSet = parent::parseText($tree [$i1], Tags::CHAR);
        }
        if (isset($tree [$i1] [1])) {
            $sub2 = $tree [$i1] [1];
            if (($i2 = parent::findTag($sub2, Tags::VERSION)) !== false) {
                $this->VerNbr = parent::parseText($sub2 [$i2], Tags::VERSION);
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
    public function __toString()
    {
        $str = __CLASS__
            . '(CharacterSet->' . $this->CharacterSet
            . ', VerNbr->' . $this->VerNbr . ')';
        return $str;
    }
}

