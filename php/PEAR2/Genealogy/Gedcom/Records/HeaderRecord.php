<?php
/**
 * PEAR2\Genealogy\Gedcom\Records\HeaderRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: HeaderRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Records;
use PEAR2\Genealogy\Gedcom\Structures;
use PEAR2\Genealogy\Gedcom\Structures\EntityAbstract;
use PEAR2\Genealogy\Gedcom\Tags;

/**
 * HeaderRecord class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class HeaderRecord extends EntityAbstract
{

    /**
     *
     * @var SourceSysteme
     */
    var $SourceSystem;
    /**
     *
     * @var string
     */
    var $DestinationSystem;
    /**
     *
     * @var string
     */
    var $TransmissionDateTime;
    /**
     *
     * @var string
     */
    var $SubmitterId;
    /**
     *
     * @var string
     */
    var $SubmissionId;
    /**
     *
     * @var string
     */
    var $Filename;
    /**
     *
     * @var string
     */
    var $Copyright;
    /**
     *
     * @var string
     */
    var $Language;
    /**
     *
     * @var CharacterSet
     */
    var $CharacterSet;
    /**
     *
     * @var GedC
     */
    var $GedC;
    /**
     *
     * @var string
     */
    var $PlaceForm;
    /**
     *
     * @var Note
     */
    var $Note;

    /**
     * Initializes complex attributes
     *
     * @return none
     */
    public function __construct()
    {
        $this->SourceSystem = new Structures\SourceSystem();
        $this->CharacterSet = new Structures\CharacterSet();
        $this->GedC = new Structures\GedC();
        $this->Note = new Structures\Note();
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
        $gedRec = $lvl . ' ' . Tags::HEADER;
        $lvl2 = $lvl + 1;

        $gedRec .= "\n" . $this->SourceSystem->toGedcom($lvl2, null);

        if (isset($this->DestinationSystem) && $this->DestinationSystem != '') {
            $gedRec .= "\n" . $lvl2
                . ' ' . Tags::DEST . ' ' . $this->DestinationSystem;
        }
        if (isset($this->TransmissionDateTime)
            && $this->TransmissionDateTime != ''
        ) {
            $d = explode(' ', $this->TransmissionDateTime);
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::DATE . ' ' . $d[0];
            if (isset($d[1])) {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::TIME . ' ' . $d[1];
            }
        }
        if (isset($this->SubmitterId) && $this->SubmitterId != '') {
            $gedRec .= "\n" . $lvl2
                . ' ' . Tags::SUBMITTER . ' @' . $this->SubmitterId . '@';
        }

        if (isset($this->SubmissionId) && $this->SubmissionId != '') {
            $gedRec .= "\n" . $lvl2
                . ' ' . Tags::SUBMISSION . ' @' . $this->SubmissionId . '@';
        }
        if (isset($this->Filename)  && $this->Filename != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::FILE . ' ' . $this->Filename;
        }
        if (isset($this->Copyright) && $this->Copyright != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::COPYRIGHT . ' ' . $this->Copyright;
        }
        $str = $this->GedC->toGedcom($lvl2, null);
        if (isset($str) && $str !='' && strpos($str, Tags::VERSION) !== false) {
            $gedRec .= "\n" . $str;
        }
        $str = $this->CharacterSet->toGedcom($lvl2, null);
        if (isset($str) && $str !='') {
            $gedRec .= "\n" . $str;
        }
        if (isset($this->Language) && $this->Language != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::LANGUAGE . ' ' . $this->Language;
        }
        if (isset($this->PlaceForm) && $this->PlaceForm != '') {
            $gedRec .= "\n" . $lvl2 . ' ' . Tags::PLACE
            . "\n" .($lvl2+1) . ' ' . Tags::FORM . ' ' . $this->PlaceForm;
        }
        $str = $this->Note->toGedcom($lvl2, null);
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
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            $this->SourceSystem->parseTree($sub2, null);
            if (($i1=parent::findTag($sub2, Tags::DEST))!==false) {
                $this->DestinationSystem
                    = parent::parseText($sub2 [$i1], Tags::DEST);
            }
            if (($i1=parent::findTag($sub2, Tags::DATE))!==false) {
                $this->TransmissionDateTime
                    = parent::parseText($sub2 [$i1], Tags::DATE);
                if (isset($sub2[$i1][1])) {
                    if (($i2=parent::findTag($sub2[$i1][1], Tags::TIME)) !== false) {
                        $this->TransmissionDateTime .= ' '
                            . parent::parseText($sub2 [$i1][1][$i2], Tags::TIME);
                    }
                }
            }
            if (($i1=parent::findTag($sub2, Tags::FILE))!==false) {
                $this->Filename
                    = parent::parseText($sub2 [$i1], Tags::FILE);
            }
            if (($i1=parent::findTag($sub2, Tags::COPYRIGHT))!==false) {
                $this->Copyright
                    = parent::parseText($sub2 [$i1], Tags::COPYRIGHT);
            }
            if (($i1=parent::findTag($sub2, Tags::LANGUAGE))!==false) {
                $this->Language
                    = parent::parseText($sub2 [$i1], Tags::LANGUAGE);
            }
            if (($i1=parent::findTag($sub2, Tags::SUBMITTER))!==false) {
                $this->SubmitterId
                    = parent::parsePtrId($sub2 [$i1], Tags::SUBMITTER);
            }
            if (($i1=parent::findTag($sub2, Tags::SUBMISSION))!==false) {
                $this->SubmissionId
                    = parent::parsePtrId($sub2 [$i1], Tags::SUBMISSION);
            }
            $this->CharacterSet->parseTree($sub2, null);
            $this->GedC->parseTree($sub2, null);
            $this->Note->parseTree($sub2, null);

            if (($i1=parent::findTag($sub2, Tags::PLACE))!==false) {
                if (isset($sub2[$i1][1])) {
                    if (($i2=parent::findTag($sub2[$i1][1], Tags::FORM)) !== false) {
                        $this->PlaceForm
                            = parent::parseText($sub2 [$i1][1][$i2], Tags::FORM);
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
        $str = __CLASS__
        . '(SourceSystem->' . $this->SourceSystem
        . ', DestinationSystem->' . $this->DestinationSystem
        . ', TransmissionDateTime->' . $this->TransmissionDateTime
        . ', SubmitterId->' . $this->SubmitterId
        . ', SubmissionId->' . $this->SubmissionId
        . ', Filename->' . $this->Filename
        . ', Copyright->' . $this->Copyright
        . ', Language->' . $this->Language
        . ', CharacterSet->' . $this->CharacterSet
        . ', GedC->' . $this->GedC
        . ', PlaceForm->' . $this->PlaceForm
        . ', Note->' . $this->Note
        . ')';
        return $str;
    }
}
?>