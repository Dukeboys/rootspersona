<?php
/**
 * \Genealogy\Gedcom\Records\IndividualRecord
 *
 * PHP version 5
 *
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: IndividualRecord.php 306 2010-04-13 22:16:26Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */





/**
 * IndividualRecord class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */
class IndividualRecord extends EntityAbstract
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
    var $Restriction;
    /**
     *
     * @var array
     */
    var $Names = array();
    /**
     *
     * @var string
     */
    var $Gender;
    /**
     *
     * @var array
     */
    var $Events;
    /**
     *
     * @var array
     */
    var $Attributes;
    /**
     *
     * @var array
     */
    var $LdsOrdinances;
    /**
     *
     * @var array
     */
    var $ChildFamilyLinks = array();
    /**
     *
     * @var array
     */
    var $SpouseFamilyLinks = array();
    /**
     *
     * @var array
     */
    var $SubmitterLinks = array();
    /**
     *
     * @var array
     */
    var $Associations = array();
    /**
     *
     * @var array
     */
    var $Aliases = array();
    /**
     *
     * @var array
     */
    var $AncestorInterests = array();
    /**
     *
     * @var array
     */
    var $DescendantInterests = array();
    /**
     *
     * @var string
     */
    var $PermRecFileNbr;
    /**
     *
     * @var string
     */
    var $AncFileNbr;
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
     *
     * @var array
     */
    var $Notes = array();
    /**
     *
     * @var array
     */
    var $Citations = array();
    /**
     *
     * @var array
     */
    var $MediaLinks = array();

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
     * Returns the default name
     *
     * @return string default (first) Full name
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function getFullName() {
        if(isset($this->Names[0]->Name->Full))
        return $this->Names[0]->Name->Full;
    }

    /**
     * Returns the nth instance of a specific event type
     *
     * @param string $tag    event type of interest
     * @param int    $offset instance nbr
     */
    public function getEvent($tag, $offset=1) {
        $events = $this->Events;
        $idx = 1;
        foreach($events as $event) {
            if($event->Tag === $tag
            || ($event->Tag === 'EVEN'
            && $event->Type === $tag)) {
                if($offset == $idx) {
                    return $event;
                }
                $idx++;
            }
        }
        return false;
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
    public function toGedcom($lvl=0, $ver='')
    {
        $gedRec = '';
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }

        if (strpos($ver, '5.5.1') == 0) {
            $gedRec = $lvl . ' @' .$this->Id. '@ ' . Tags::INDIVIDUAL;
            $lvl2 = $lvl + 1;
            if (isset($this->Restriction) && $this->Restriction != '') {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::RESTRICTION .' ' .$this->Restriction;
            }
            for ($i=0; $i<count($this->Names); $i++) {
                $gedRec .= "\n" . $this->Names[$i]->toGedcom($lvl2, $ver);
            }
            if (isset($this->Gender) && $this->Gender != '') {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::GENDER .' ' .$this->Gender;
            }
            for ($i=0; $i<count($this->Events); $i++) {
                $gedRec .= "\n" . $this->Events[$i]->toGedcom($lvl2, $ver);
            }
            for ($i=0; $i<count($this->Attributes); $i++) {
                $gedRec .= "\n" . $this->Attributes[$i]->toGedcom($lvl2, $ver);
            }
            //  for ($i=0; $i<count($this->LdsOrdinances); $i++) {
            //     $str .= "\n" . $this->LdsOrdinances[$i]->toGedcom($lvl2, $ver);
            //  }
            for ($i=0; $i<count($this->ChildFamilyLinks); $i++) {
                $gedRec .= "\n"
                . $this->ChildFamilyLinks[$i]
                ->toGedcom($lvl2, $ver, Tags::CHILDFAMILY);
            }
            for ($i=0; $i<count($this->SpouseFamilyLinks); $i++) {
                $gedRec .= "\n"
                . $this->SpouseFamilyLinks[$i]
                ->toGedcom($lvl2, $ver, Tags::SPOUSEFAMILY);
            }
            for ($i=0; $i<count($this->SubmitterLinks); $i++) {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::SUBMITTER . ' @' . $this->SubmitterLinks[$i] . '@';
            }
            for ($i=0; $i<count($this->Associations); $i++) {
                $gedRec .= "\n" . $this->Associations[$i]->toGedcom($lvl2, $ver);
            }
            for ($i=0; $i<count($this->Aliases); $i++) {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::ALIAS . ' @' . $this->Aliases[$i] . '@';
            }
            for ($i=0; $i<count($this->AncestorInterests); $i++) {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::ANCI . ' @' . $this->AncestorInterests[$i] . '@';
            }
            for ($i=0; $i<count($this->DescendantInterests); $i++) {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::DESI . ' @' . $this->DescendantInterests[$i] . '@';
            }
            if (isset($this->PermRecFileNbr) && $this->PermRecFileNbr != '') {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::PERMFILE .' ' .$this->PermRecFileNbr;
            }
            if (isset($this->AncFileNbr) && $this->AncFileNbr != '') {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::ANCFILE .' ' .$this->AncFileNbr;
            }
            for ($i=0; $i<count($this->UserRefNbrs); $i++) {
                $gedRec .= "\n" . $lvl2
                . ' ' . Tags::USERFILE . ' ' . $this->UserRefNbrs[$i]['Nbr'];
                if (isset($this->UserRefNbrs[$i]['Type'])) {
                    $gedRec .= "\n" .($lvl2+1)
                    . ' ' . Tags::TYPE . ' ' . $this->UserRefNbrs[$i]['Type'];
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
            for ($i=0; $i<count($this->Citations); $i++) {
                $gedRec .= "\n" . $this->Citations[$i]->toGedcom($lvl2, $ver);
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
        $this->Ver = $ver;
        $this->Id = parent::parseRefId($tree[0], Tags::INDIVIDUAL);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            if (($i1=parent::findTag($sub2, Tags::RESTRICTION))!==false) {
                $this->Restriction
                = parent::parseText($sub2 [$i1], Tags::RESTRICTION);
            }

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::FULL, $off))!==false) {
                $tmp = new PersonalName();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->Names[] = $tmp;
                $off = $i1 + 1;
            }

            if (($i1=parent::findTag($sub2, Tags::GENDER))!==false) {
                $this->Gender = parent::parseText($sub2 [$i1], Tags::GENDER);
            }

            $tmp = new Event();
            $this->Events = $tmp->parseTreeToArray($sub2, $ver);
            $tmp = new Fact();
            $this->Attributes = $tmp->parseTreeToArray($sub2, $ver);
            //TODO add support for LdsOrdinances
            //$this->LdsOrdinances = LdsOrdinance::parseTreeToArray($sub2, $ver);

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::CHILDFAMILY, $off))!==false) {
                $tmp = new FamilyLink();
                $tmp->parseTree(array($sub2[$i1]), $ver, Tags::CHILDFAMILY);
                $this->ChildFamilyLinks[] = $tmp;
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::SPOUSEFAMILY, $off))!==false) {
                $tmp = new FamilyLink();
                $tmp->parseTree(array($sub2[$i1]), $ver, Tags::SPOUSEFAMILY);
                $this->SpouseFamilyLinks[] = $tmp;
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::SUBMITTER, $off))!==false) {
                $this->SubmitterLinks[]
                = parent::parsePtrId($sub2 [$i1], Tags::SUBMITTER);
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::ASSOCIATION, $off))!==false) {
                $tmp = new Association();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->Associations[] = $tmp;
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::ALIAS, $off))!==false) {
                $this->Aliases[]
                = parent::parsePtrId($sub2 [$i1], Tags::ALIAS);
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::ANCI, $off))!==false) {
                $this->AncestorInterests[]
                = parent::parsePtrId($sub2 [$i1], Tags::ANCI);
                $off = $i1 + 1;
            }
            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::DESI, $off))!==false) {
                $this->DescendantInterests[]
                = parent::parsePtrId($sub2 [$i1], Tags::DESI);
                $off = $i1 + 1;
            }
            if (($i1=parent::findTag($sub2, Tags::PERMFILE))!==false) {
                $this->PermRecFileNbr
                = parent::parseText($sub2 [$i1], Tags::PERMFILE);
            }
            if (($i1=parent::findTag($sub2, Tags::ANCFILE))!==false) {
                $this->AncFileNbr = parent::parseText($sub2 [$i1], Tags::ANCFILE);
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
            while (($i1=parent::findTag($sub2, Tags::CITE, $off))!==false) {
                $tmp = new Citation();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->Citations[] = $tmp;
                $off = $i1 + 1;
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
        . ', Restriction->' . $this->Restriction
        . ', Names->(';

        for ($i=0; $i<count($this->Names); $i++) {
            $str .= "\n" . $this->Names[$i];
        }
        $str .= '), Gender->' . $this->Gender
        . ', Events->(';
        for ($i=0; $i<count($this->Events); $i++) {
            $str .= "\n" . $this->Events[$i];
        }
        $str .= '), Attributes->(';
        for ($i=0; $i<count($this->Attributes); $i++) {
            $str .= "\n" . $this->Attributes[$i];
        }
        $str .= '), LdsOrdinances->(';
        for ($i=0; $i<count($this->LdsOrdinances); $i++) {
            $str .= "\n" . $this->LdsOrdinances[$i];
        }
        $str .= '), ChildFamilyLinks->(';
        for ($i=0; $i<count($this->ChildFamilyLinks); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->ChildFamilyLinks[$i];
        }
        $str .= '), SpouseFamilyLinks->(';
        for ($i=0; $i<count($this->SpouseFamilyLinks); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->SpouseFamilyLinks[$i];
        }
        $str .= '), SubmitterLinks->(';
        for ($i=0; $i<count($this->SubmitterLinks); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->SubmitterLinks[$i];
        }
        $str .= '), Associations->(';
        for ($i=0; $i<count($this->Associations); $i++) {
            $str .= "\n" . $this->Associations[$i];
        }
        $str .= '), Aliases->(';
        for ($i=0; $i<count($this->Aliases); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->Aliases[$i];
        }
        $str .= '), AncestorInterests->(';
        for ($i=0; $i<count($this->AncestorInterests); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->AncestorInterests[$i];
        }
        $str .= '), DescendantInterests->(';
        for ($i=0; $i<count($this->DescendantInterests); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= $this->DescendantInterests[$i];
        }
        $str .= '), PermRecFileNbr->' . $this->PermRecFileNbr
        . ', AncFileNbr->' . $this->AncFileNbr;

        $str .= ', UserRefNbrs->(';
        for ($i=0; $i<count($this->UserRefNbrs); $i++) {
            $str .= "UserRefNbr->" . $this->UserRefNbrs[$i]['Nbr']
            . ' (' . $this->UserRefNbrs[$i]['Type'] . ')';
        }
        $str .= '), AutoRecId->' . $this->AutoRecId
        . ', ChangeDate->' . $this->ChangeDate
        . ', Citations->(';
        for ($i=0; $i<count($this->Citations); $i++) {
            $str .= "\n" . $this->Citations[$i];
        }
        $str .= '), MediaLinks->(';
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