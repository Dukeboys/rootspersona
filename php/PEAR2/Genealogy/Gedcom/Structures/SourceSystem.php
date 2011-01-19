<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\SourceSystem
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SourceSystem.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * SourceSystem class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class SourceSystem extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $SystemId;
    /**
     *
     * @var string
     */
    var $VerNbr;
    /**
     *
     * @var unknown_type
     */
    var $ProductName;
    /**
     *
     * @var Corporation
     */
    var $Corporation;
    /**
     *
     * @var Data
     */
    var $Data;

    /**
     * constructor
     */
    function __construct()
    {
        $this->Corporation = new Corporation();
        $this->Data = new Data();
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
        $gedRec = '';
        if (isset($this->SystemId) && $this->SystemId != '') {
            $gedRec .= $lvl . ' ' . Tags::SOURCE . ' ' . $this->SystemId;
        }
        $lvl2 = $lvl + 1;
        if (isset($this->VerNbr) && $this->VerNbr != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::VERSION . ' ' . $this->VerNbr;
        }
        if (isset($this->ProductName) && $this->ProductName != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::NAME . ' ' . $this->ProductName;
        }
        $str = $this->Corporation->toGedcom($lvl2, null);
        if (isset($str) && $str !='') {
            $gedRec .= "\n" . $str;
        }
        $str = $this->Data->toGedcom($lvl2, null);
        if (isset($str) && $str !='') {
            $gedRec .= "\n" . $str;
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
        if (($i1=parent::findTag($tree, Tags::SOURCE))!== false) {
            $this->SystemId = parent::parseText($tree[$i1], Tags::SOURCE);
            if (isset($tree[$i1][1])) {
                $sub2 = $tree [$i1][1];
                if (($i2 = parent::findTag($sub2, Tags::VERSION)) !== false) {
                    $this->VerNbr = parent::parseText($sub2 [$i2], Tags::VERSION);
                }
                if (($i2 = parent::findTag($sub2, Tags::NAME)) !== false) {
                    $this->ProductName = parent::parseText($sub2 [$i2], Tags::NAME);
                }
                $this->Corporation->parseTree($sub2, $ver);
                $this->Data->parseTree($sub2, $ver);
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
        . '(SystemId->' . $this->SystemId
        . ', VerNbr->' . $this->VerNbr
        . ', ProductName->' . $this->ProductName
        . ', Corporation->' . $this->Corporation
        . ', Data->' . $this->Data
        . ')';
        return $str;
    }
}
