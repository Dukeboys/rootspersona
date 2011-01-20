<?php
/**
 * \Genealogy\Gedcom\FamilyLink
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: FamilyLink.php 296 2010-03-31 14:12:11Z ed4becky $
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
class FamilyLink  extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $FamilyId;
    /**
     *
     * @var string
     */
    var $LinkageType;
    /**
     *
     * @var string
     */
    var $LinkageStatus;
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
     * @param string $tag FAMS or FAMC
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function toGedcom($lvl, $ver, $tag=Tags::SPOUSEFAMILY)
    {
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->FamilyId) && $this->FamilyId != '') {
                $gedRec .= $lvl . ' ' . $tag . ' @' . $this->FamilyId  . '@';
                $lvl2 = $lvl+1;
                if ($tag == Tags::CHILDFAMILY) {
                    if (isset($this->LinkageType) && $this->LinkageType != '') {
                        $gedRec .= "\n"
                        .$lvl2 . ' ' . Tags::LINKTYPE . ' ' . $this->LinkageType;
                    }
                    if (isset($this->LinkageStatus) && $this->LinkageStatus != '') {
                        $gedRec .= "\n" .$lvl2
                        . ' ' . Tags::LINKSTATUS . ' ' . $this->LinkageStatus;
                    }
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
     * @param string $tag  FAMS or FAMC
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver, $tag=Tags::SPOUSEFAMILY)
    {
        $this->Ver =$ver;
        if (($i1=parent::findTag($tree, $tag))!==false) {
            $this->FamilyId = parent::parsePtrId($tree[$i1], $tag);
            if(isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                $off = 0;
                while (($i2=parent::findTag($sub2, Tags::NOTE, $off))!==false) {
                    $tmp = new Note();
                    $tmp->parseTree(array($sub2[$i2]), $ver);
                    $this->Notes[] = $tmp;
                    $off = $i2 + 1;
                }
                if ($tag==Tags::CHILDFAMILY) {
                    if (($i2=parent::findTag($sub2, Tags::LINKTYPE))!==false) {
                        $this->LinkageType
                        = parent::parseText($sub2[$i2], Tags::LINKTYPE);
                    }
                    if (($i2=parent::findTag($sub2, Tags::LINKSTATUS))!==false) {
                        $this->LinkageStatus
                        = parent::parseText($sub2[$i2], Tags::LINKSTATUS);
                    }
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
        $str = __CLASS__ . '(FamilyId->' . $this->FamilyId;
        if (isset($this->LinkageType) && $this->LinkageType != '') {
            $str .= ', LinkageType->' . $this->LinkageType;
        }
        if (isset($this->LinkageStatus) && $this->LinkageStatus != '') {
            $str .= ', LinkageStatus->' . $this->LinkageStatus;
        }

        $str .= ', Notes->(';

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= '))';

        return $str;
    }
}
