<?php
/**
 * \Genealogy\Gedcom\MediaLink
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: MediaLink.php 291 2010-03-30 19:38:34Z ed4becky $
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
class MediaLink extends EntityAbstract
{
    var $Id;
    var $Title; // MediaLink and Mediarecord treat Title differently per spec
    var $MediaFiles = array();

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
            if (isset($this->Id) && $this->Id != '') {
                $gedRec .= $lvl . ' ' . rpTags::MEDIA . ' @' . $this->Id . '@';
            } else {
                $gedRec .= $lvl . ' ' . rpTags::MEDIA;
                $lvl2 = $lvl + 1;
                if (isset($this->Title) && $this->Title != '') {
                    $gedRec .= "\n" . $lvl2 . ' ' . rpTags::TITLE . ' ' . $this->Title;
                }
                for ($i=0; $i<count($this->MediaFiles); $i++) {
                    $gedRec .= "\n" . $this->MediaFiles[$i]->toGedcom($lvl2, $ver);
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
        if (($i1=parent::findTag($tree, rpTags::MEDIA))!==false) {
            $str = parent::parsePtrId($tree[$i1], rpTags::MEDIA);
            if (isset($str) && $str != '') {
                $this->Id = $str;
            } else {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, rpTags::TITLE))!==false) {
                    $this->Title = parent::parseText($sub2[$i2], rpTags::TITLE);
                }
                $off = 0;
                while (($i1=parent::findTag($sub2, rpTags::FILE, $off))!==false) {
                    $tmp = new MediaFile();
                    $tmp->parseTree(array($sub2[$i1]), $ver);
                    $this->MediaFiles[] = $tmp;
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
        . '(Version->' . $this->Ver;
        if (isset($this->Id) && $this->Id != '') {
            $str .= ', Id->' . $this->Id;
        } else {
            $str .= ', Title->' . $this->Title
            . ', MediaFiles->(';
            for ($i=0; $i<count($this->MediaFiles); $i++) {
                $str .= "\n" . $this->MediaFiles[$i];
            }
            $str .= ')';
        }
        $str .= ')';

        return $str;
    }
}

?>