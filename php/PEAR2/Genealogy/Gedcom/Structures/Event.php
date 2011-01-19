<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\Event
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Event.php 307 2010-12-25 23:35:23Z ed4becky $
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
class Event extends FactDetail
{
    var $_TYPES = array(
    'ADOP' => 'Adoption',
    'BIRT' => 'Birth',
    'BAPM' => 'Baptism',
    'BARM' => 'Bar Mitzvah',
    'BASM' => 'Bas Mitzvah',
    'BLES' => 'Blessing',
    'BURI' => 'Burial',
    'CENS' => 'Census',
    'CHR' => 'Christening',
    'CHRA' => 'Adult Christening',
    'CONF' => 'Confirmation',
    'CREM' => 'Cremation',
    'DEAT' => 'Death',
    'EMIG' => 'Emmigration',
    'FCOM' => 'First Communion',
    'GRAD' => 'Graduation',
    'IMMI' => 'Immigration',
    'NATU' => 'Naturalization',
    'ORDN' => 'Ordnance',
    'RETI' => 'Retirement',
    'PROB' => 'Probate',
    'WILL' => 'Will',
    'ANUL' => 'Annulment',
    'DIV' => 'Divorce',
    'DIVF' => 'Divorce Filed',
    'ENG' => 'Engagement',
    'MARB' => 'Marriage Bann',
    'MARC' => 'Marriage Constract',
    'MARR' => 'Marriage',
    'MARL' => 'Marriage License',
    'MARS' => 'Marriage Settlement',
    'RESI' => 'Residence',
    'EVEN' => 'Event'
    );

    /**
     * constructor
     */
    function __construct()
    {
        parent::__construct();
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
    public function parseTreeToArray($tree, $ver)
    {
        $events = array();
        $keys = array_keys($this->_TYPES);
        foreach ($keys as $tag) {
            $off = 0;
            while (($i1=parent::findTag($tree, $tag, $off))!==false) {
                $event = new Event();
                $event->Ver =$ver;
                $event->Tag = $tag;
                $event->Descr = parent::parseText($tree[$i1], $tag);
                //$tmp = $this->_TYPES;
                //$event->Type = $tmp[$tag];
                if(isset($tree[$i1][1]))
                	$event->parseTreeDetail($tree[$i1][1], $ver);
                $events[] = $event;
                $off = $i1 + 1;
            }
        }
        return $events;
    }

    /**
     * getter for _TYPES array
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->_TYPES;
    }
}
