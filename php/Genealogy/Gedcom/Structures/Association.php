<?php
/**
 * \Genealogy\Gedcom\Association
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Association.php 291 2010-03-30 19:38:34Z ed4becky $
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

class Association  extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $AssociateId;

    /**
     *
     * @var string
     */
    var $Relationship;
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
            if (isset($this->AssociateId) && $this->AssociateId != '') {
                $gedRec .= $lvl . ' ' . Tags::ASSOCIATION
                    . ' @' . $this->AssociateId . '@';

                $lvl2 = $lvl+1;
                if (isset($this->Relationship) && $this->Relationship != '') {
                    $gedRec .= "\n" . $lvl2 . ' '
                        . Tags::RELATIONSHIP . ' ' . $this->Relationship;
                }
                for ($i=0; $i<count($this->Citations); $i++) {
                    $gedRec .= "\n" . $this->Citations[$i]->toGedcom($lvl2, $ver);
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
        if (($i1=parent::findTag($tree, Tags::ASSOCIATION))!==false) {
            $this->AssociateId = parent::parsePtrId(
                $tree[$i1], Tags::ASSOCIATION
            );
            if (isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, Tags::RELATIONSHIP))!==false) {
                    $this->Relationship
                        = parent::parseText($sub2[$i2], Tags::RELATIONSHIP);
                }
                $off = 0;
                while (
                    ($i1=parent::findTag($sub2, Tags::CITE, $off))!==false
                    ) {
                    $tmp = new Citation();
                    $tmp->parseTree(array($sub2[$i1]), $ver);
                    $this->Citations[] = $tmp;
                    $off = $i1 + 1;
                }
                $off = 0;
                while (
                    ($i1=parent::findTag($sub2, Tags::NOTE, $off))!==false
                    ) {
                    $tmp = new Note();
                    $tmp->parseTree(array($sub2[$i1]), $ver);
                    $this->Notes[] = $tmp;
                    $off = $i1 + 1;
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
        . '(Version->' . $this->Ver
        . ', AssociateId->' . $this->AssociateId
        . ', Relationship->' . $this->Relationship
        . ', Citations->(';

        for ($i=0; $i<count($this->Citations); $i++) {
            $str .= "\n" . $this->Citations[$i];
        }
        $str .= '), Notes->(';
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= '))';

        return $str;
    }
}
