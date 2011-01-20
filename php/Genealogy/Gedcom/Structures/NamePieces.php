<?php
/**
 * \Genealogy\Gedcom\NamePieces
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: NamePieces.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */



/**
 * NamePieces class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */

class NamePieces extends EntityAbstract
{

    var $Prefix;
    var $Given;
    var $NickName;
    var $SurnamePrefix;
    var $Surname;
    var $Suffix;
    var $Citations = array();
    var $Notes = array();

    /**
     * Returns a concatenated string of a 'Full' name
     *
     * @return string The full name built from the individiual pieces.
     */
    public function getFullName() {
        $str =  $this->Prefix
        . ' ' . $this->Given
        . (isset($this->NickName)?' (' . $this->$NickName . ')': '')
        . ' ' . $this->SurnamePrefix
        . ' ' . $this->Surname
        . ' ' . $this->Suffix;

        return trim(str_replace('  ',' ',$str));
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
            if (isset($this->Prefix) && $this->Prefix != '') {
                $gedRec .= $lvl . ' ' . Tags::PREFIX . ' ' . $this->Prefix;
            }

            if (isset($this->Given) && $this->Given != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::GIVEN . ' ' .  $this->Given;
            }
            if (isset($this->NickName) && $this->NickName != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::NICK . ' ' .  $this->NickName;
            }
            if (isset($this->SurnamePrefix) && $this->SurnamePrefix != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::SURPREFIX
                . ' ' .  $this->SurnamePrefix;
            }
            if (isset($this->Surname) && $this->Surname != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::SURNAME . ' ' .  $this->Surname;
            }
            if (isset($this->Suffix) && $this->Suffix != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl . ' ' . Tags::SUFFIX . ' ' .  $this->Suffix;
            }
            for ($i=0; $i<count($this->Citations); $i++) {
                $str .= "\n" . $this->Citations[$i]->toGedcom($lvl, $ver);
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
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        $this->Ver =$ver;
        if (($i1=parent::findTag($tree, Tags::PREFIX))!==false) {
            $this->Prefix = parent::parseText($tree[$i1], Tags::PREFIX);
        }
        if (($i1=parent::findTag($tree, Tags::GIVEN))!==false) {
            $this->Given = parent::parseText($tree[$i1], Tags::GIVEN);
        }
        if (($i1=parent::findTag($tree, Tags::NICK))!==false) {
            $this->NickName = parent::parseText($tree[$i1], Tags::NICK);
        }
        if (($i1=parent::findTag($tree, Tags::SURPREFIX))!==false) {
            $this->SurnamePrefix = parent::parseText($tree[$i1], Tags::SURPREFIX);
        }
        if (($i1=parent::findTag($tree, Tags::SURNAME))!==false) {
            $this->Surname = parent::parseText($tree[$i1], Tags::SURNAME);
        }
        if (($i1=parent::findTag($tree, Tags::SUFFIX))!==false) {
            $this->Suffix = parent::parseText($tree[$i1], Tags::SUFFIX);
        }

        $off = 0;
        while (($i1=parent::findTag($tree, Tags::NOTE, $off))!==false) {
            $tmp = new Note();
            $this->Notes[] = $tmp->parseTree(array($tree[$i1]), $ver);
            $off = $i1 + 1;
        }

        $off = 0;
        while (($i1=parent::findTag($tree, Tags::CITE, $off))!==false) {
            $tmp = new Citation();
            $this->Citations[] = $tmp->parseTree(array($tree[$i1]), $ver);
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
        $str = __CLASS__
        . '(Version->' . $this->Ver
        . ', Prefix->' . $this->Prefix
        . ', Given->' . $this->Given
        . ', NickName->' . $this->NickName
        . ', SurnamePrefix->' . $this->SurnamePrefix
        . ', Surname->' . $this->Surname
        . ', Suffix->' . $this->Suffix;

        for ($i=0; $i<count($this->Citations); $i++) {
            $str .= "\n" . $this->Citations[$i];
        }

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= ')';
        return $str;
    }
}
