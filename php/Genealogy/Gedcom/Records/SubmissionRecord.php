<?php
/**
 * \Genealogy\Gedcom\Records\SubmissionRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SubmissionRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */





/**
 * SubmissionRecord class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
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
        $this->ChangeDate = new ChangeDate();
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
            $gedRec = $lvl . ' @' .$this->Id. '@ ' . rpTags::SUBMISSION;
            $lvl2 = $lvl + 1;
            if (isset($this->FamilyFileName) && $this->FamilyFileName != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::FAMILYFILE
                    .' ' .$this->FamilyFileName;
            }
            if (isset($this->TempleCode) && $this->TempleCode != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::TEMPLECODE
                    .' ' .$this->TempleCode;
            }
            if (isset($this->GenerationsAncestors)
                && $this->GenerationsAncestors != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::ANCESTORS
                    .' ' .$this->GenerationsAncestors;
            }
            if (isset($this->GenerationsDescendants)
                && $this->GenerationsDescendants != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::DESCENDANTS
                    .' ' .$this->GenerationsDescendants;
            }
            if (isset($this->OrdinanceProcessFlag)
                && $this->OrdinanceProcessFlag != ''
            ) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::ORDINANCEFLAG .' ' .$this->OrdinanceProcessFlag;
            }

            if (isset($this->AutoRecId) && $this->AutoRecId != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . rpTags::AUTORECID .' ' .$this->AutoRecId;
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
        $this->Id = parent::parseRefId($tree[0], rpTags::SUBMISSION);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            if (($i1=parent::findTag($sub2, rpTags::SUBMITTER))!==false) {
                $this->SubmitterId
                    = parent::parsePtrId($sub2 [$i1], rpTags::SUBMITTER);
            }
            if (($i1=parent::findTag($sub2, rpTags::FAMILYFILE))!==false) {
                $this->FamilyFileName
                    = parent::parseText($sub2 [$i1], rpTags::FAMILYFILE);
            }
            if (($i1=parent::findTag($sub2, rpTags::TEMPLECODE))!==false) {
                $this->TempleCode
                    = parent::parseText($sub2 [$i1], rpTags::TEMPLECODE);
            }
            if (($i1=parent::findTag($sub2, rpTags::ANCESTORS))!==false) {
                $this->GenerationsAncestors
                    = parent::parseText($sub2 [$i1], rpTags::ANCESTORS);
            }
            if (($i1=parent::findTag($sub2, rpTags::DESCENDANTS))!==false) {
                $this->GenerationsDescendants
                    = parent::parseText($sub2 [$i1], rpTags::DESCENDANTS);
            }
            if (($i1=parent::findTag($sub2, rpTags::ORDINANCEFLAG))!==false) {
                $this->OrdinanceProcessFlag
                    = parent::parseText($sub2 [$i1], rpTags::ORDINANCEFLAG);
            }

            if (($i1=parent::findTag($sub2, rpTags::AUTORECID))!==false) {
                $this->AutoRecId = parent::parseText($sub2 [$i1], rpTags::AUTORECID);
            }

            if (($i1=parent::findTag($sub2, rpTags::CHANGEDATE))!==false) {
                $this->ChangeDate->parseTree(array($sub2[$i1]), $ver);
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