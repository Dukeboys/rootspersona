<?php
/**
 * \Genealogy\Gedcom\SourceData
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SourceData.php 273 2010-03-26 13:48:06Z ed4becky $
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

class SourceData  extends EntityAbstract
{

    var $RecordedEvents = array();
    var $ResponsibleAgency;
    var $Notes = array();

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
            $gedRec .= $lvl . ' ' . Tags::DATA;
            for ($i=0; $i<count($this->RecordedEvents); $i++) {
                $gedRec .= "\n" . ($lvl+1) . ' '
                . Tags::EVENT . ' '
                . $this->RecordedEvents[$i]['Types'];
                if (isset($this->RecordedEvents[$i]['Date'])
                    && $this->RecordedEvents[$i]['Date'] != ''
                ) {
                    $gedRec .= "\n" . ($lvl+2) . ' '
                    . Tags::DATE . ' '
                    . $this->RecordedEvents[$i]['Date'];
                }
                if (isset($this->RecordedEvents[$i]['Jurisdiction'])
                    && $this->RecordedEvents[$i]['Jurisdiction'] != ''
                ) {
                    $gedRec .= "\n" . ($lvl+2) . ' '
                    . Tags::PLACE . ' '
                    . $this->RecordedEvents[$i]['Jurisdiction'];
                }
            }

            if (isset($this->ResponsibleAgency)) {
                $gedRec .= "\n" .($lvl+1)
                    . ' ' . Tags::AGENCY . ' ' . $this->ResponsibleAgency;
            }
            for ($i=0; $i<count($this->Notes); $i++) {
                $gedRec .= "\n" . $this->Notes[$i]->toGedcom(($lvl+1), $ver);
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
        if (($i1=parent::findTag($tree, Tags::DATA))!==false) {
            $sub2 = $tree[$i1][1];
            $off = 0;
            while (($i2=parent::findTag($sub2, Tags::EVENT, $off))!==false) {
                $event = array('Types'=>'', 'Date'=>'', 'Jurisdiction'=>'');
                $event['Types'] = parent::parseText($sub2[$i2], Tags::EVENT);
                $sub2Sub = $sub2[$i2][1];
                if (($i3=parent::findTag($sub2Sub, Tags::DATE))!==false) {
                    $event['Date']
                        = parent::parseText($sub2Sub[$i3], Tags::DATE);
                }
                if (($i3=parent::findTag($sub2Sub, Tags::PLACE))!==false) {
                    $event['Jurisdiction']
                        = parent::parseText($sub2Sub[$i3], Tags::PLACE);
                }
                $this->RecordedEvents[] = $event;
                $off = $i2 + 1;
            }

            if (($i2=parent::findTag($sub2, Tags::AGENCY))!==false) {
                $this->ResponsibleAgency
                    = parent::parseText($sub2[$i2], Tags::AGENCY);
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
        $str = __CLASS__ . '(RecordedEvents->(';
        for ($i=0; $i<count($this->RecordedEvents); $i++) {
            if ($i > 0) {
                $str .= ', ';
            }
            $str .= "RecordedEvent->" . $this->RecordedEvents[$i]['Types'] . ' '
            .  $this->RecordedEvents[$i]['Date']
            . ' (' . $this->RecordedEvents[$i]['Jurisdiction'] . ')';
        }
        $str .= '), ResponsibleAgency->' . $this->ResponsibleAgency
        . ', Notes->(';
        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }

        $str .= '))';

        return $str;
    }
}
