<?php
/**
 * PEAR2\Genealogy\Gedcom\Records\SubmissionRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SubmissionRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Records;
use PEAR2\Genealogy\Gedcom\Structures;
use PEAR2\Genealogy\Gedcom\Structures\EntityAbstract;
use PEAR2\Genealogy\Gedcom\Tags;

/**
 * SubmissionRecord class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
class SubmissionRecord extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $Id;
    /**
     *
     * @var string
     */
    var $FamilyFileName;
    /**
     *
     * @var string
     */
    var $TempleCode;
    /**
     *
     * @var int
     */
    var $GenerationsAncestors;
    /**
     *
     * @var int
     */
    var $GenerationsDescendants;
    /**
     *
     * @var string
     */
    var $OrdinanceProcessFlag;
    /**
     *
     * @var string
     */
    var $SubmitterId;
    /**
     *
     * @var string
     */
    var $AutoRecId;
    /**
     *
     * @var string
     */
    var $ChangeDate;
    /**
     *
     * @var array
     */
    var $Notes = array();

    /**
     * Initializes complex attributes
     *
     * @return none
     */
    public function __construct()
    {
        $this->ChangeDate = new Structures\ChangeDate();
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
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }

        if (strpos($ver, '5.5.1') == 0) {
            $gedRec = $lvl . ' @' .$this->Id. '@ ' . Tags::SUBMISSION;
            $lvl2 = $lvl + 1;
            if (isset($this->FamilyFileName) && $this->FamilyFileName != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::FAMILYFILE
                    .' ' .$this->FamilyFileName;
            }
            if (isset($this->TempleCode) && $this->TempleCode != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::TEMPLECODE
                    .' ' .$this->TempleCode;
            }
            if (isset($this->GenerationsAncestors)
                && $this->GenerationsAncestors != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::ANCESTORS
                    .' ' .$this->GenerationsAncestors;
            }
            if (isset($this->GenerationsDescendants)
                && $this->GenerationsDescendants != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::DESCENDANTS
                    .' ' .$this->GenerationsDescendants;
            }
            if (isset($this->OrdinanceProcessFlag)
                && $this->OrdinanceProcessFlag != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::ORDINANCEFLAG .' ' .$this->OrdinanceProcessFlag;
            }

            if (isset($this->AutoRecId) && $this->AutoRecId != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::AUTORECID .' ' .$this->AutoRecId;
            }
            $tmp = $this->ChangeDate->toGedcom($lvl2, $ver);
            if (isset($tmp) && $tmp != '') {
                $gedRec .= "\n" . $tmp;
            }
            for ($i=0; $i<count($this->Notes); $i++) {
                $str .= "\n" . $this->Notes[$i]->toGedcom($lvl2, $ver);
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
        $this->Id = parent::parseRefId($tree[0], Tags::SUBMISSION);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            if (($i1=parent::findTag($sub2, Tags::SUBMITTER))!==false) {
                $this->SubmitterId
                    = parent::parsePtrId($sub2 [$i1], Tags::SUBMITTER);
            }
            if (($i1=parent::findTag($sub2, Tags::FAMILYFILE))!==false) {
                $this->FamilyFileName
                    = parent::parseText($sub2 [$i1], Tags::FAMILYFILE);
            }
            if (($i1=parent::findTag($sub2, Tags::TEMPLECODE))!==false) {
                $this->TempleCode
                    = parent::parseText($sub2 [$i1], Tags::TEMPLECODE);
            }
            if (($i1=parent::findTag($sub2, Tags::ANCESTORS))!==false) {
                $this->GenerationsAncestors
                    = parent::parseText($sub2 [$i1], Tags::ANCESTORS);
            }
            if (($i1=parent::findTag($sub2, Tags::DESCENDANTS))!==false) {
                $this->GenerationsDescendants
                    = parent::parseText($sub2 [$i1], Tags::DESCENDANTS);
            }
            if (($i1=parent::findTag($sub2, Tags::ORDINANCEFLAG))!==false) {
                $this->OrdinanceProcessFlag
                    = parent::parseText($sub2 [$i1], Tags::ORDINANCEFLAG);
            }

            if (($i1=parent::findTag($sub2, Tags::AUTORECID))!==false) {
                $this->AutoRecId = parent::parseText($sub2 [$i1], Tags::AUTORECID);
            }

            if (($i1=parent::findTag($sub2, Tags::CHANGEDATE))!==false) {
                $this->ChangeDate->parseTree(array($sub2[$i1]), $ver);
            }

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::NOTE, $off))!==false) {
                $tmp = new Structures\Note();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->Notes[] = $tmp;
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
        . '(Id->' . $this->Id
        . ', SubmitterId->' . $this->SubmitterId
        . ', FamilyFileName->' . $this->FamilyFileName
        . ', TempleCode->' . $this->TempleCode
        . ', GenerationsAncestors->' . $this->GenerationsAncestors
        . ', GenerationsDescendants->' . $this->GenerationsDescendants
        . ', OrdinanceProcessFlag->' . $this->OrdinanceProcessFlag
        . ', AutoRecId->' . $this->AutoRecId
        . ', ChangeDate->' . $this->ChangeDate
        . ', Notes->(';

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }
        $str .= '))';
        return $str;
    }
}
?>