<?php
/**
 * \Genealogy\Gedcom\LdsSealing
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: LdsSealing.php 291 2010-03-30 19:38:34Z ed4becky $
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
class LdsSealing extends EntityAbstract
{
    // TODO add code
    var $_TYPES = array(
    'BAPL' => '',
    'CONL' => '',
    'ENDL' => '',
    'SLGC' => ''
    );


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
    public static function parseTreeToArray($tree, $ver)
    {
        $events = array();
        foreach (LdsSealing::_TYPES as $tag) {
            $off = 0;
            while (($i1=parent::findTag($tree, $tag, $off))!==false) {
                $event = new LdsSealing();
                $event->Code = $tag;
                $tmp = LdsSealing::_TYPES;
                $event->Type = $tmp[$tag];
                $event->toGedcomDetail($tree[$i1], $ver);
                $events[] = $event;
                $off = $i1 + 1;
            }
        }
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
