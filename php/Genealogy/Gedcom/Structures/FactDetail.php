<?php
/**
 * \Genealogy\Gedcom\FactDetail
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: FactDetail.php 296 2010-03-31 14:12:11Z ed4becky $
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
abstract class FactDetail extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $Tag;
    /**
     *
     * @var string
     */
    var $Descr;
    /**
     *
     * @var string
     */
    var $Type;
    /**
     *
     * @var string
     */
    var $Date;
    /**
     *
     * @var Place
     */
    var $Place;
    /**
     *
     * @var Address
     */
    var $Address;
    /**
     *
     * @var string
     */
    var $Age;
    /**
     *
     * @var string
     */
    var $RespAgency;
    /**
     *
     * @var string
     */
    var $ReligiousAffiliation;
    /**
     *
     * @var string
     */
    var $Restriction;
    /**
     *
     * @var string
     */
    var $Cause;
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
     *
     * @var array
     */
    var $Notes = array();

    /**
     * constructor
     */
    function __construct()
    {
        $this->Place = new Place();
        $this->Address = new Address();
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
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->Tag) && $this->Tag != '') {
                $gedRec .= $lvl . ' ' . $this->Tag;
                if (isset($this->Descr) && $this->Descr != '') {
                    $gedRec .= ' ' . $this->Descr;
                }
                $lvl++;
                $gedRec .= $this->toGedcomDetail($lvl, $ver);
            }
        }
        return $gedRec;
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
    protected function toGedcomDetail($lvl, $ver)
    {
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->Type) && $this->Type != '') {
                $gedRec .= "\n" . $lvl . ' ' . Tags::TYPE . ' ' . $this->Type;
            }
            if (isset($this->Date) && $this->Date != '') {
                $gedRec .= "\n" . $lvl . ' ' . Tags::DATE . ' ' . $this->Date;
            }
            $str = $this->Place->toGedcom($lvl, $ver);
            if (isset($str) && $str != '') {
                $gedRec .= "\n" . $str;
            }
            $str = $this->Address->toGedcom($lvl, $ver);
            if (isset($str) && $str != '') {
                $gedRec .= "\n" . $str;
            }
            if (isset($this->Age) && $this->Age != '') {
                $gedRec .= $lvl . ' ' . Tags::AGE . ' ' . $this->Age;
            }
            if (isset($this->RespAgency) && $this->RespAgency != '') {
                $gedRec .= $lvl . ' ' . Tags::AGENCY . ' ' . $this->RespAgency;
            }
            if (isset($this->ReligiousAffiliation)
                && $this->ReligiousAffiliation != ''
            ) {
                $gedRec .= $lvl . ' '
                    . Tags::RELIGION . ' ' . $this->ReligiousAffiliation;
            }
            if (isset($this->Restriction) && $this->Restriction != '') {
                $gedRec .= $lvl . ' ' . Tags::RESTRICTION . ' ' . $this->Restriction;
            }
            if (isset($this->Cause) && $this->Cause != '') {
                $gedRec .= $lvl . ' ' . Tags::CAUSE . ' ' . $this->Cause;
            }
            for ($i=0; $i<count($this->Citations); $i++) {
                $str .= "\n" . $this->Citations[$i]->toGedcom($lvl, $ver);
            }
            for ($i=0; $i<count($this->MediaLinks); $i++) {
                $str .= "\n" . $this->MediaLinks[$i]->toGedcom($lvl, $ver);
            }
            for ($i=0; $i<count($this->Notes); $i++) {
                $str .= "\n" . $this->Notes[$i]->toGedcom($lvl, $ver);
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
     * @param string $tag  tag of interest, deafulats to EVEN
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver, $tag=Tags::EVENT)
    {
        $this->Ver =$ver;
        if (($i1=parent::findTag($tree, $tag))!==false) {
            $this->Tag = $tag;
            $this->Descr = parent::parseText($tree[$i1], $tag);
            $sub2 = $tree[$i1][1];
            $this->parseTreeDetail($sub2, $ver);
        }
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
    protected function parseTreeDetail($tree, $ver)
    {
        if (($i1=parent::findTag($tree, Tags::TYPE))!==false) {
            $this->Type = parent::parseText($tree[$i1], Tags::TYPE);
        }
        if (($i1=parent::findTag($tree, Tags::DATE))!==false) {
            $this->Date = parent::parseText($tree[$i1], Tags::DATE);
        }
        if (($i1=parent::findTag($tree, Tags::ADDRESS))!==false) {
            $this->Address->parseTree(array($tree[$i1]), $ver);
        }
        if (($i1=parent::findTag($tree, Tags::PLACE))!==false) {
            $this->Place->parseTree(array($tree[$i1]), $ver);
        }
        if (($i1=parent::findTag($tree, Tags::RELIGION))!==false) {
            $this->ReligiousAffiliation
                = parent::parseText($tree[$i1], Tags::RELIGION);
        }
        if (($i1=parent::findTag($tree, Tags::AGENCY))!==false) {
            $this->RespAgency = parent::parseText($tree[$i1], Tags::AGENCY);
        }
        if (($i1=parent::findTag($tree, Tags::AGE))!==false) {
            $this->Age = parent::parseText($tree[$i1], Tags::AGE);
        }
        if (($i1=parent::findTag($tree, Tags::RESTRICTION))!==false) {
            $this->Restriction = parent::parseText($tree[$i1], Tags::RESTRICTION);
        }
        if (($i1=parent::findTag($tree, Tags::CAUSE))!==false) {
            $this->Cause = parent::parseText($tree[$i1], Tags::CAUSE);
        }
        $this->Place->parseTree($tree, $ver);
        $this->Address->parseTree($tree, $ver);
        $off = 0;
        while (($i1=parent::findTag($tree, Tags::CITE, $off))!==false) {
            $tmp = new Citation();
            $tmp->parseTree(array($tree[$i1]), $ver);
            $this->Citations[] = $tmp;
            $off = $i1 + 1;
        }
        $off = 0;
        while (($i1=parent::findTag($tree, Tags::MEDIA, $off))!==false) {
            $tmp = new MediaLink();
            $tmp->parseTree(array($tree[$i1]), $ver);
            $this->MediaLinks[] = $tmp;
            $off = $i1 + 1;
        }
        $off = 0;
        while (($i1=parent::findTag($tree, Tags::NOTE, $off))!==false) {
            $tmp = new Note();
            $tmp->parseTree(array($tree[$i1]), $ver);
            $this->Notes[] = $tmp;
            $off = $i1 + 1;
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
        . ', Tag->' . $this->Tag
        . ', Description->' . $this->Descr
        . ', Type->' . $this->Type
        . ', Date->' . $this->Date
        . ', Place->' . $this->Place
        . ', Address->' . $this->Address
        . ', Age->' .$this->Age
        . ', RespAgency->' .$this->RespAgency
        . ', ReligiousAffiliation->' . $this->ReligiousAffiliation
        . ', Restriction->' .$this->Restriction
        . ', Cause->' .$this->Cause;

        for ($i=0; $i<count($this->Citations); $i++) {
            $str .= "\n" . $this->Citations[$i];
        }
        for ($i=0; $i<count($this->MediaLinks); $i++) {
            $str .= "\n" . $this->MediaLinks[$i];
        }
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= ')';

        return $str;
    }

    /**
     * return an array of relevant child tags
     *
     * @return array
     */
    public abstract function getTypes();
}
