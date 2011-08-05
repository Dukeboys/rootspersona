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
class RP_Source_Data extends RP_Entity_Abstract {
    var $recorded_events = array();
    var $responsible_agency;
	var $notes = array();
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
    public function to_gedcom( $lvl, $ver ) {
        if ( ! isset( $ver )
        || $ver === '' ) {
            $ver = $this->ver;
        }
        $ged_rec = '';
        if ( strpos( $ver, '5.5.1' ) == 0 ) {
            $ged_rec .= $lvl . ' ' . Rp_Tags::DATA;
            for ( $i = 0;
	$i < count( $this->recorded_events );
	$i++ ) {
                $ged_rec .= "\n" . ( $lvl + 1 ) . ' ' . Rp_Tags::EVENT . ' ' . $this->recorded_events[$i]['Types'];
                if ( isset( $this->recorded_events[$i]['Date'] )
                && $this->recorded_events[$i]['Date'] != '' ) {
                    $ged_rec .= "\n" . ( $lvl + 2 ) . ' ' . Rp_Tags::DATE . ' ' . $this->recorded_events[$i]['Date'];
                }
                if ( isset( $this->recorded_events[$i]['Jurisdiction'] )
                && $this->recorded_events[$i]['Jurisdiction'] != '' ) {
                    $ged_rec .= "\n" . ( $lvl + 2 ) . ' ' . Rp_Tags::PLACE . ' ' . $this->recorded_events[$i]['Jurisdiction'];
                }
            }
            if ( isset( $this->responsible_agency ) ) {
                $ged_rec .= "\n" . ( $lvl + 1 ) . ' ' . Rp_Tags::AGENCY . ' ' . $this->responsible_agency;
            }
            for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
                $ged_rec .= "\n" . $this->notes[$i]->to_gedcom( ( $lvl + 1 ), $ver );
            }
        }
        return $ged_rec;
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
    public function parse_tree( $tree, $ver ) {
        $this->ver = $ver;if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::DATA ) ) !== false ) {
            if ( isset( $tree[$i1][1] ) ) {
                $sub2 = $tree[$i1][1];
                $off = 0;
                while ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::EVENT, $off ) ) !== false ) {
                    $event = array( 'Types' => '', 'Date' => '', 'Jurisdiction' => '' );
                    $event['Types'] = parent::parse_text( $sub2[$i2], Rp_Tags::EVENT );
                    if ( isset( $sub2[$i2][1] ) ) {
                        $sub2_sub = $sub2[$i2][1];
                        if ( ( $i3 = parent::find_tag( $sub2_sub, Rp_Tags::DATE ) ) !== false ) {
                            $event['Date'] = parent::parse_text( $sub2_sub[$i3], Rp_Tags::DATE );
                        }
                        if ( ( $i3 = parent::find_tag( $sub2_sub, Rp_Tags::PLACE ) ) !== false ) {
                            $event['Jurisdiction'] = parent::parse_text( $sub2_sub[$i3], Rp_Tags::PLACE );
                        }
                    }
                    $this->recorded_events[] = $event;
	$off = $i2 + 1;
                }
                if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::AGENCY ) ) !== false ) {
                    $this->responsible_agency = parent::parse_text( $sub2[$i2], Rp_Tags::AGENCY );
                }
                $off = 0;
                while ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::NOTE, $off ) ) !== false ) {
                    $tmp = new RP_Note();
                    $tmp->parse_tree( array( $sub2[$i2] ), $ver );
                    $this->notes[] = $tmp;
	$off = $i2 + 1;
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
    public function __toString() {
        $str = __CLASS__ . '(RecordedEvents->(';
        for ( $i = 0;
	$i < count( $this->recorded_events );
	$i++ ) {
            if ( $i > 0 ) {
                $str .= ', ';
            }
            $str .= "RecordedEvent->" . $this->recorded_events[$i]['Types'] . ' ' . $this->recorded_events[$i]['Date'] . ' (' . $this->recorded_events[$i]['Jurisdiction'] . ')';
        }
        $str .= '), ResponsibleAgency->' . $this->responsible_agency . ', Notes->(';
        for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
            $str .= "\n" . $this->notes[$i];
        }
        $str .= '))';
        return $str;
    }
}
