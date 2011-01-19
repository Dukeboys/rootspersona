<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\GedC
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: GedC.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * GedC class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class GedC extends EntityAbstract
{
    /**
     * An identifier that represents the version level assigned
     * to the associated product. It is defined and changed by
     * the creators of the product.
     *
     * @var unknown_type
     */
    var $VerNbr;

    /**
     * The GEDCOM form used to construct this transmission.
     * (LINEAGE-LINKED)
     */
    var $Form = 'LINEAGE-LINKED';

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
        $gedRec = $lvl . ' ' . Tags::GEDC;
        $lvl2 = $lvl + 1;
        if (isset($this->VerNbr) && $this->VerNbr != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::VERSION . ' ' . $this->VerNbr;
        }
        if (isset($this->Form) && $this->Form != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::FORM . ' ' . $this->Form;
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
        if (($i1=parent::findTag($tree, Tags::GEDC))!==false)
        if (isset($tree [$i1] [1])) {
            $sub2 = $tree [$i1] [1];
            if (($i2=parent::findTag($sub2, Tags::VERSION)) !== false) {
                $this->VerNbr = parent::parseText($sub2 [$i2], Tags::VERSION);
            }
            if (($i2 = parent::findTag($sub2, Tags::FORM)) !== false) {
                $this->Form = parent::parseText($sub2 [$i2], Tags::FORM);
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
        $str = __CLASS__ . '(VerNbr->' . $this->VerNbr
        . ', Form->' . $this->Form . ')';
        return $str;
    }
}

