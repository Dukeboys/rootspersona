<?php
/**
 * \Genealogy\Gedcom\MediaFile
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: MediaFile.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */



/**
 * MediaFile class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */

class MediaFile  extends EntityAbstract
{
    var $RefNbr;
    var $Format;
    var $FormatType;
    var $Title;

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
            if (isset($this->RefNbr) && $this->RefNbr != '') {
                $gedRec .= $lvl . ' ' . Tags::FILE . ' ' . $this->RefNbr;

                $lvl2 = $lvl+1;
                if (isset($this->Format) && $this->Format != '') {
                    $gedRec .= "\n" . $lvl2
                        . ' ' . Tags::FORMAT . ' ' . $this->Format;
                    if (isset($this->FormatType) && $this->FormatType != '') {
                        $gedRec .= "\n" .($lvl2+1)
                            . ' ' . Tags::TYPE . ' ' . $this->FormatType;
                    }
                }
                if (isset($this->Title) && $this->Title != '') {
                    $gedRec .= "\n" . $lvl2 . ' ' . Tags::TITLE . ' ' . $this->Title;
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
        if (($i1=parent::findTag($tree, Tags::FILE))!==false) {
            $this->RefNbr = parent::parseText($tree[$i1], Tags::FILE);
            if (isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, Tags::FORMAT))!==false) {
                    $this->Format = parent::parseText($sub2[$i2], Tags::FORMAT);
                    if (isset($sub2[$i2][1])) {
                        if (($i3=parent::findTag($sub2[$i2][1], Tags::TYPE))!==false
                        ) {
                            $this->FormatType
                                = parent::parseText($sub2[$i2][1][$i3], Tags::TYPE);
                        }
                    }
                }
                if (($i2=parent::findTag($sub2, Tags::TITLE))!==false) {
                    $this->Title = parent::parseText($sub2[$i2], Tags::TITLE);
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
        . '(RefNbr->' . $this->RefNbr
        . ', Format->' . $this->Format
        . ', FormatType->' . $this->FormatType
        . ', Title->' . $this->Title
        . ')';

        return $str;
    }
}
