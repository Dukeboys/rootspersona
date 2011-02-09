<?php
/**
 * \Genealogy\Gedcom\rpData
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: rpData.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */




/**
 * rpData class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */

class rpData extends EntityAbstract
{
    /**
     *
     * @var string
     */
    var $SourceName;
    /**
     *
     * @var string
     */
    var $Date;
    /**
     *
     * @var string
     */
    var $Copyright;

    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     * should be generated
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
        if (isset($this->SourceName) && $this->SourceName != '') {
            $gedRec .= $lvl . ' ' . rpTags::DATA . ' ' . $this->SourceName;
        }

        $lvl2 = $lvl + 1;
        if (isset($this->Date) && $this->Date != '') {
            if ($gedRec != '') {
                $gedRec .= "\n";
            }
            $gedRec .= $lvl2 . ' ' . rpTags::DATE . ' ' . $this->Date;
        }
        if (isset($this->Copyright) && $this->Copyright != '') {
            $gedRec .= "\n"
                . parent::toConTag($this->Copyright, rpTags::COPYRIGHT, $lvl2);
        }
        return $gedRec;
    }

    /**
     * Extracts attribute contents from a parent tree object
     *
     * @param array  $tree an array containing an array from which the
     * object rpData should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * rpData is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        if (($i1 = parent::findTag($tree, rpTags::DATA)) !== false) {
            $this->SourceName = parent::parseText($tree [$i1], rpTags::DATA);
            if (isset($tree [$i1] [1])) {
                $sub2 = $tree [$i1] [1];
                if (($i2 = parent::findTag($sub2, rpTags::DATE)) !== false) {
                    $this->Date .= parent::parseText($sub2 [$i2], rpTags::DATE);
                }
                if (($i2 = parent::findTag($sub2, rpTags::COPYRIGHT)) !== false) {
                    $this->Copyright
                        .=  parent::parseConTag($sub2 [$i2], rpTags::COPYRIGHT);
                }
            }
        }
    }

    /**
     * Creates a string representation of the object
     *
     * @return string  contains string representation of each
     * public field in the object
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function __toString()
    {
        $str = __CLASS__
        . '(SourceName->' . $this->SourceName
        . ', Date->' . $this->Date
        . ', Copyright->' . $this->Copyright . ')';
        return $str;
    }
}
