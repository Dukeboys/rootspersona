<?php
/**
 * \Genealogy\Gedcom\RepositoryCitation
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: RepositoryCitation.php 291 2010-03-30 19:38:34Z ed4becky $
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
class RepositoryCitation  extends EntityAbstract
{

    var $RepositoryId;
    var $CallNbrs = array();
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
            $str = '';
            if (isset($this->RepositoryId) && $this->RepositoryId != '') {
                $str = ' @' . $this->RepositoryId . '@';
            }
            $gedRec .= $lvl . ' ' . Tags::REPOSITORY . $str;
            $lvl2 = $lvl+1;
            for ($i=0; $i<count($this->CallNbrs); $i++) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::CALLNBR
                    . ' ' . $this->CallNbrs[$i]['Nbr'];
                if (isset($this->CallNbrs[$i]['Media'])) {
                    $gedRec .= "\n" . ($lvl2+1)
                        . ' ' . Tags::MEDIATYPE
                        . ' ' . $this->CallNbrs[$i]['Media'];
                }
            }
            for ($i=0; $i<count($this->Notes); $i++) {
                $gedRec .= "\n" . $this->Notes[$i]->toGedcom($lvl2, $ver);
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
        if (($i1=parent::findTag($tree, Tags::REPOSITORY))!==false) {
            $this->RepositoryId = parent::parsePtrId($tree[$i1], Tags::REPOSITORY);
            $sub2 = $tree[$i1][1];

            $off = 0;
            $idx = 0;
            while (($i2=parent::findTag($sub2, Tags::CALLNBR, $off))!==false) {
                $this->CallNbrs[$idx]['Nbr'] = parent::parseText($sub2 [$i2], Tags::CALLNBR);
                if (isset($sub2 [$i2][1])) {
                    if (($i3=parent::findTag($sub2 [$i2][1], Tags::MEDIATYPE))!==false
                    ) {
                        $this->CallNbrs[$idx]['Media']
                            = parent::parseText(
                                $sub2 [$i2][1][$i3], Tags::MEDIATYPE
                            );
                    }
                }
                $off = $i2 + 1;
                $idx++;
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
        $str = __CLASS__ . '(RepositoryId->' . $this->RepositoryId
        . ', Notes->(';

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }
        $str .= '), CallNbrs->(';

        for ($i=0; $i<count($this->CallNbrs); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= "(" . $this->CallNbrs[$i]['Nbr']
            . ", " . $this->CallNbrs[$i]['Media'] . ')';
        }

        $str .= '))';

        return $str;
    }
}
