<?php
/**
 * \Genealogy\Gedcom\rpEvent
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: rpEvent.php 307 2010-12-25 23:35:23Z ed4becky $
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
class RP_Event extends RP_Fact_Detail {
    var $_TYPES = array( 'ADOP' => 'Adoption', 'BIRT' => 'Birth', 'BAPM' => 'Baptism', 'BARM' => 'Bar Mitzvah', 'BASM' => 'Bas Mitzvah', 'BLES' => 'Blessing', 'BURI' => 'Burial', 'CENS' => 'Census', 'CHR' => 'Christening', 'CHRA' => 'Adult Christening', 'CONF' => 'Confirmation', 'CREM' => 'Cremation', 'DEAT' => 'Death', 'EMIG' => 'Emmigration', 'FCOM' => 'First Communion', 'GRAD' => 'Graduation', 'IMMI' => 'Immigration', 'NATU' => 'Naturalization', 'ORDN' => 'Ordnance', 'RETI' => 'Retirement', 'PROB' => 'Probate', 'WILL' => 'Will', 'ANUL' => 'Annulment', 'DIV' => 'Divorce', 'DIVF' => 'Divorce Filed', 'ENG' => 'Engagement', 'MARB' => 'Marriage Bann', 'MARC' => 'Marriage Constract', 'MARR' => 'Marriage', 'MARL' => 'Marriage License', 'MARS' => 'Marriage Settlement', 'RESI' => 'Residence', 'EVEN' => 'Event' );
    /**
     * constructor
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
        $events = array();
        $keys = array_keys( $this->_TYPES );
        foreach ( $keys as $tag ) {
            $off = 0;
            while ( ( $i1 = parent::find_tag( $tree, $tag, $off ) ) !== false ) {
                $event = new RP_Event();
                $event->ver = $ver;
	$event->tag = $tag;
	$event->descr = parent::parse_text( $tree[$i1], $tag );
                //$tmp = $this->TYPES;
                //$event->Type = $tmp[$tag];
                if ( isset( $tree[$i1][1] ) )$event->parse_tree_detail( $tree[$i1][1], $ver );
                $events[] = $event;
	$off = $i1 + 1;
            }
        }
        return $events;
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
