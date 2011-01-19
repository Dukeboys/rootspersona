<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\Fact
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Fact.php 307 2010-12-25 23:35:23Z ed4becky $
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
class Fact extends FactDetail
{
    var $_TYPES = array(
    'CAST' => 'Caste',
    'EDUC' => 'Education',
    'NATI' => 'Nationality',
    'OCCU' => 'Occupation',
     'PROP' => 'Possessions',
    'RELI' => 'Religion',
    'RESI' => 'Residence',
    'TITL' => 'NobilityTitle',
    'SSN'  => 'Social Security Nbr',
    'FACT' => 'Fact'
    );

    /**
     * CONSTRUCTOR
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
        $facts = array();
        $keys = array_keys($this->_TYPES);
        foreach ($keys as $tag) {
            $off = 0;
            while (($i1=parent::findTag($tree, $tag, $off))!==false) {
                $fact = new Fact();
                $fact->Ver = $ver;
                $fact->Tag = $tag;
                $fact->Descr = parent::parseText($tree[$i1], $tag);
                //$tmp = $fact->_TYPES;
                //$fact->Type = $tmp[$tag];
                if(isset($tree[$i1][1]))
                	$fact->parseTreeDetail($tree[$i1][1], $ver);
                $facts[] = $fact;
                $off = $i1 + 1;
            }
        }
        return $facts;
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
