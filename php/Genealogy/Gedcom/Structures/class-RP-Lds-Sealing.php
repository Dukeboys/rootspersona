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
class RP_Lds_Sealing extends RP_Entity_Abstract {
    // TODO add code
    var $_TYPES = array( 'BAPL' => '', 'CONL' => '', 'ENDL' => '', 'SLGC' => '' );
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
    public static function parse_tree_to_array( $tree, $ver ) {
        $events = array();
        foreach ( Lds_Sealing::_TYPES as $tag ) {
            $off = 0;
            while ( ( $i1 = parent::find_tag( $tree, $tag, $off ) ) !== false ) {
                $event = new RP_Lds_Sealing();
                $event->code = $tag;
	$tmp = Lds_Sealing::_TYPES;
                $event->type = $tmp[$tag];
                $event->to_gedcom_detail( $tree[$i1], $ver );
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
    public function get_types() {
        return $this->_TYPES;
    }
}
