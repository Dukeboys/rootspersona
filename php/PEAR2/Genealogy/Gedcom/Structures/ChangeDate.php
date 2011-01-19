<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\ChangeDate
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: ChangeDate.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * Citation class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
class ChangeDate  extends EntityAbstract
{
    /**
     *
     * @var string
     */
    var $Date;
    /**
     *
     * @var string
     */
    var $Time;
    /**
     *
     * @var array
     */
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
            if (isset($this->Date) && $this->Date != '') {
                $gedRec .= $lvl . ' ' . Tags::CHANGEDATE;
                $lvl2 = $lvl+1;
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::DATE . ' ' . $this->Date ;
                if (isset($this->Time) && $this->Time != '') {
                    $gedRec .= "\n" .($lvl2+1)
                        . ' ' . Tags::TIME . ' ' . $this->Time;
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
        if (($i1=parent::findTag($tree, Tags::CHANGEDATE))!==false) {
            $sub2 = $tree[$i1][1];
            if (($i2=parent::findTag($sub2, Tags::DATE))!==false) {
                $this->Date = parent::parseText($sub2[$i2], Tags::DATE);
                if (isset($sub2[$i2][1])) {
                    if (($i3=parent::findTag($sub2[$i2][1], Tags::TIME))!==false) {
                        $this->Time
                            = parent::parseText($sub2[$i2][1][$i3], Tags::TIME);
                    }
                }
                $off = 0;
                while (($i2=parent::findTag($sub2, Tags::NOTE, $off))!==false) {
                    $tmp = new Note();
                    $tmp->parseTree(array($sub2[$i2]), $ver);
                    $this->Notes[] = $tmp;
                    $off = $i2 + 1;
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
        $str = __CLASS__ . '(Date->' . $this->Date
        . ' ' . $this->Time
        . ', Notes->(';

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= '))';

        return $str;
    }
}
