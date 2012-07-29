<?php
/**
 * \Genealogy\Gedcom\Fact
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Fact.php 307 2010-12-25 23:35:23Z ed4becky $
 * @link
 */
/**
 * Citation class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Fact extends RP_Fact_Detail {
    var $_TYPES = array( 'CAST' => 'Caste', 'EDUC' => 'Education', 'NATI' => 'Nationality', 'OCCU' => 'Occupation', 'PROP' => 'Possessions', 'RELI' => 'Religion', 'RESI' => 'Residence', 'TITL' => 'Nobility Title', 'SSN' => 'Social Security Nbr', 'FACT' => 'Fact' );
    /**
     * CONSTRUCTOR
     */
    function __construct() {
        parent::__construct();
    }
    /**
     * Extracts attribute contents FROM a parent tree object
     *
     * @param array  $tree an array containing an array FROM which the
     *                     object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     *                     data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree_to_array( $tree, $ver ) {
        $facts = array();
        $keys = array_keys( $this->_TYPES );
        foreach ( $keys as $tag ) {
            $off = 0;
            while ( ( $i1 = parent::find_tag( $tree, $tag, $off ) ) !== false ) {
                $fact = new RP_Fact();
                $fact->ver = $ver;
	$fact->tag = $tag;
	$fact->descr = parent::parse_text( $tree[$i1], $tag );
                //$tmp = $fact->TYPES;
                //$fact->Type = $tmp[$tag];
                if ( isset( $tree[$i1][1] ) )$fact->parse_tree_detail( $tree[$i1][1], $ver );
                $facts[] = $fact;
	$off = $i1 + 1;
            }
        }
        return $facts;
    }
    /**
     * getter for TYPES array
     *
     * @return array
     */
    public function get_types() {
        return $this->_TYPES;
    }
}
