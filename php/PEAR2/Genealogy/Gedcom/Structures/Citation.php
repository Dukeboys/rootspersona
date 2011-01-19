<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\Citation
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Citation.php 291 2010-03-30 19:38:34Z ed4becky $
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
class Citation extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $SourceId;
    /**
     *
     * @var string
     */
    var $Page;
    /**
     *
     * @var string
     */
    var $EventType;
    /**
     *
     * @var string
     */
    var $RoleInEvent;
    /**
     * The date that this event data was entered
     * into the original source document
     *
     * @var string
     */
    var $EntryDate;

    /**
     * what the original record keeper said
     *
     * @var unknown_type
     */
    var $Texts = array();
    /**
     *
     * @var int
     */
    var $Quay;
    /**
     *
     * @var array
     */
    var $MediaLinks = array();
    /**
     *
     * @var array
     */
    var $Notes = array();
    // TODO support alternate (unpreferred) format

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
            if (isset($this->SourceId) && $this->SourceId != '') {
                $gedRec .= $lvl . ' ' . Tags::CITE . ' @' . $this->SourceId . '@';
            }
            $lvl2 = $lvl + 1;
            if (isset($this->Page) && $this->Page != '') {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::PAGE . ' ' . $this->Page;
            }
            if (isset($this->EventType) && $this->EventType != '') {
                $gedRec .= "\n" . $lvl2 . ' '
                    . Tags::EVENTTYPE . ' ' . $this->EventType;
                if (isset($this->RileInEvent) && $this->RoleInEvent != '') {
                    $gedRec .= "\n" . ($lvl2+1)
                        . ' ' . Tags::ROLE . ' ' . $this->RoleInEvent;
                }
            }
            if (isset($this->EntryDate) && $this->EntryDate != ''
                || count($this->Texts) > 0
            ) {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::DATA;
                $lvl3 = $lvl2 + 1;
                if (isset($this->EntryDate) && $this->EntryDate != '') {
                    $gedRec .= "\n"
                        . $lvl3 . ' ' . Tags::DATE . ' ' . $this->EntryDate;
                }
                for ($i=0; $i<count($this->Texts); $i++) {
                    $gedRec .= "\n"
                        . parent::toConTag($this->Texts[$i], Tags::TEXT, $lvl3);
                }
            }
            if (isset($this->Quay) && $this->Quay != '') {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::QUAY . ' ' . $this->Quay;
            }
            for ($i=0; $i<count($this->MediaLinks); $i++) {
                $gedRec .= "\n" . $this->MediaLinks[$i]->toGedcom($lvl2, $ver);
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
        if (($i1=parent::findTag($tree, Tags::SOURCE))!==false) {
            $this->SourceId = parent::parsePtrId($tree[$i1], Tags::SOURCE);
            $sub2 = $tree[$i1][1];
            if (($i2=parent::findTag($sub2, Tags::PAGE))!==false) {
                $this->Page = parent::parseText($sub2[$i2], Tags::PAGE);
            }
            if (($i2=parent::findTag($sub2, Tags::QUAY))!==false) {
                $this->Quay = parent::parseText($sub2[$i2], Tags::QUAY);
            }
            if (($i2=parent::findTag($sub2, Tags::EVENTTYPE))!==false) {
                $this->EventType = parent::parseText($sub2[$i2], Tags::EVENTTYPE);
                if (isset($sub2[$i2][1])) {
                    if (($i3=parent::findTag($sub2[$i2][1], Tags::ROLE))!==false) {
                        $this->RoleInEvent
                            = parent::parseText($sub2[$i2][1][$i3], Tags::ROLE);
                    }
                }
            }
            if (($i2=parent::findTag($sub2, Tags::DATA))!==false) {
                $sub3 = $sub2[$i2][1];
                if (isset($sub3)) {
                    if (($i3=parent::findTag($sub3, Tags::DATE))!==false) {
                        $this->EntryDate = parent::parseText($sub3[$i3], Tags::DATE);
                    }
                    $off = 0;
                    while (($i3=parent::findTag($sub3, Tags::TEXT, $off))!==false) {
                        $this->Texts[]
                            = parent::parseConTag($sub3[$i3], Tags::TEXT);
                        $off = $i3 + 1;
                    }
                }
            }
            $off = 0;
            while (($i2=parent::findTag($sub2, Tags::MEDIA, $off))!==false) {
                $tmp = new MediaLink();
                $tmp->parseTree(array($sub2[$i2]), $ver);
                $this->MediaLinks[] = $tmp;
                $off = $i2 + 1;
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
        $str = __CLASS__ . '(Version->' . $this->Ver
        . ', SourceId->' . $this->SourceId
        . ', Page->' . $this->Page
        . ', EventType->' . $this->EventType
        . ', RoleInEvent->' . $this->RoleInEvent
        . ', EntryDate->' . $this->EntryDate
        . ', Texts->(';

        for ($i=0; $i<count($this->Texts); $i++) {
            $str .= "\nText->(" . $this->Texts[$i] . ')';

        }
        $str .= '), Quay->' . $this->Quay
        . ', MediaLinks->(';
        for ($i=0; $i<count($this->MediaLinks); $i++) {
            $str .= "\n" . $this->MediaLinks[$i];
        }
        $str .= '), Notes->(';
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= '))';

        return $str;
    }
}
?>