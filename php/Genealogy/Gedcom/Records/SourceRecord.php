<?php
/**
 * \Genealogy\Gedcom\Records\SourceRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SourceRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */





/**
 * SourceRecord class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */
class SourceRecord extends EntityAbstract
{
    /**
     *
     * @var string
     */
    var $Id;
    /**
     *
     * @var SourceData
     */
    var $SourceData = array();
    /**
     *
     * @var string
     */
    var $Author;
    /**
     *
     * @var string
     */
    var $Title;
    /**
     *
     * @var string
     */
    var $AbbreviatedTitle;
    /**
     *
     * @var string
     */
    var $PublicationFacts;
    /**
     *
     * @var string
     */
    var $Text;
    /**
     *
     * @var array
     */
    var $RepositoryCitations = array();
    /**
     *
     * @var array
     */
    var $MediaLinks = array();
    /**
     *
     * @var array
     */
    var $Notes= array();
    /**
     *
     * @var array
     */
    var $UserRefNbrs = array();
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
            $gedRec = $lvl . ' @' .$this->Id. '@ ' . Tags::SOURCE;
            $lvl2 = $lvl + 1;

            for ($i=0; $i<count($this->SourceData); $i++) {
                $gedRec .= "\n" . $this->SourceData[$i]->toGedcom($lvl2, $ver);
            }
            if (isset($this->Author) && $this->Author != '') {
                $gedRec .= "\n"
                    . parent::toConTag($this->Author, Tags::AUTHOR, $lvl2);
            }
            if (isset($this->Title) && $this->Title != '') {
                $gedRec .= "\n"
                    . parent::toConTag($this->Title, Tags::TITLE, $lvl2);
            }
            if (isset($this->AbbreviatedTitle) && $this->AbbreviatedTitle != '') {
                $gedRec .= "\n"
                    . parent::toConTag($this->AbbreviatedTitle, Tags::ABBR, $lvl2);
            }
            if (isset($this->PublicationFacts) && $this->PublicationFacts != '') {
                $gedRec .= "\n"
                    . parent::toConTag(
                        $this->PublicationFacts, Tags::PUBLICATION, $lvl2
                    );
            }
            if (isset($this->Text) && $this->Text != '') {
                $gedRec .= "\n"
                    . parent::toConTag($this->Text, Tags::TEXT, $lvl2);
            }
            for ($i=0; $i<count($this->RepositoryCitations); $i++) {
                $gedRec .= "\n"
                    . $this->RepositoryCitations[$i]->toGedcom($lvl2, $ver);
            }
            for ($i=0; $i<count($this->UserRefNbrs); $i++) {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::USERFILE
                    . ' ' . $this->UserRefNbrs[$i]['Nbr'];
                if (isset($this->UserRefNbrs[$i]['Type'])) {
                    $gedRec .= "\n" .($lvl2+1)
                        . ' ' . Tags::TYPE
                        . ' ' . $this->UserRefNbrs[$i]['Type'];
                }
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
                $gedRec .= "\n" . $this->Notes[$i]->toGedcom($lvl2, $ver);
            }
            for ($i=0; $i<count($this->MediaLinks); $i++) {
                $gedRec .= "\n" . $this->MediaLinks[$i]->toGedcom($lvl2, $ver);
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
        $this->Id = parent::parseRefId($tree[0], Tags::SOURCE);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::DATA, $off))!==false) {
                $tmp = new SourceData();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->SourceData[] = $tmp;
                $off = $i1 + 1;
            }

            if (($i1=parent::findTag($sub2, Tags::AUTHOR))!==false) {
                $this->Author
                    = parent::parseConTag($sub2 [$i1], Tags::AUTHOR);
            }
            if (($i1=parent::findTag($sub2, Tags::TITLE))!==false) {
                $this->Title
                    = parent::parseConTag($sub2 [$i1], Tags::TITLE);
            }
            if (($i1=parent::findTag($sub2, Tags::ABBR))!==false) {
                $this->AbbreviatedTitle
                    = parent::parseConTag($sub2 [$i1], Tags::ABBR);
            }
            if (($i1=parent::findTag($sub2, Tags::PUBLICATION))!==false) {
                $this->PublicationFacts
                    = parent::parseConTag($sub2 [$i1], Tags::PUBLICATION);
            }
            if (($i1=parent::findTag($sub2, Tags::TEXT))!==false) {
                $this->Text = parent::parseConTag($sub2 [$i1], Tags::TEXT);
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::REPOSITORY, $off))!==false) {
                $tmp = new RepositoryCitation();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->RepositoryCitations[] = $tmp;
                $off = $i1 + 1;
            }
            if (($i1=parent::findTag($sub2, Tags::USERFILE))!==false) {
                $this->UserRefNbrs[]['Nbr']
                    = parent::parseText($sub2 [$i1], Tags::USERFILE);
                if (isset($sub2[$i1][1])) {
                    if (($i2=parent::findTag($sub2[$i1][1], Tags::TYPE)) !== false) {
                        $this->UserRefNbrs[count($this->UserRefNbrs)-1]['Type']
                            = parent::parseText($sub2 [$i1][1][$i2], Tags::TYPE);
                    }
                }
            }
            if (($i1=parent::findTag($sub2, Tags::AUTORECID))!==false) {
                $this->AutoRecId = parent::parseText($sub2 [$i1], Tags::AUTORECID);
            }

            if (($i1=parent::findTag($sub2, Tags::CHANGEDATE))!==false) {
                $this->ChangeDate->parseTree(array($sub2[$i1]), $ver);
            }

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::MEDIA, $off))!==false) {
                $tmp = new MediaLink();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->MediaLinks[] = $tmp;
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::NOTE, $off))!==false) {
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
        . ', Author->' . $this->Author
        . ', Title->' . $this->Title
        . ', AbbreviatedTitle->' . $this->AbbreviatedTitle
        . ', PublicationFacts->' . $this->PublicationFacts
        . ', Text->' . $this->Text
        . ', SourceData->('
        ;
        for ($i=0; $i<count($this->SourceData); $i++) {
            $str .= "\n" . $this->SourceData[$i];
        }
        $str .= '), SourceRepositoryCitations->(';
        for ($i=0; $i<count($this->RepositoryCitations); $i++) {
            $str .= "\n" . $this->RepositoryCitations[$i];
        }
        $str .= '), UserRefNbrs->(';
        for ($i=0; $i<count($this->UserRefNbrs); $i++) {
            $str .= "UserRefNbr->"
                . $this->UserRefNbrs[$i]['Nbr']
                . ' (' . $this->UserRefNbrs[$i]['Type'] . ')';
        }
        $str .= '), AutoRecId->' . $this->AutoRecId
        . ', ChangeDate->' . $this->ChangeDate
        . ', MediaLinks->(';

        for ($i=0; $i<count($this->MediaLinks); $i++) {
            $str .= "\n" . $this->MediaLinks[$i];
        }
        $str .= '), Notes->(' ;
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }
        $str .= '))';
        return $str;
    }
}
?>
