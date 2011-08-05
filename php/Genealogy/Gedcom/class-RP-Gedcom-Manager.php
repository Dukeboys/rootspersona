<?php
/**
 * \Genealogy\Gedcom\GedcomManager
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: GedcomManager.php 303 2010-04-12 12:40:46Z ed4becky $
 * @link
 */
/**
 * Main class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Gedcom_Manager extends RP_Parser {
    /**
     * Loads and parses a GEDCOM formatted file into
     * Objects
     *
     * @param $Filename
     *
     * @return void
     * @throws FileException
     * @access public
     * @since Method available since Release 0.0.1
     */
    function parse( $_filename, $callback_class = null ) {
        parent::parse( $_filename, $callback_class );
    }
    /**
     * return the number of individual
     *
     * @access public
     * @return integer
     */
    function get_number_of_individuals() {
        return count( $this->gedcom_objects['IndiRecs'] );
    }
    /**
     * return the number of family
     *
     * @access public
     * @return integer
     */
    function get_number_of_families() {
        return count( $this->gedcom_objects['FamRecs'] );
    }
    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function get_number_of_objects() {
        return count( $this->gedcom_objects['MediaRecs'] );
    }
    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function get_number_of_notes() {
        return count( $this->gedcom_objects['NoteRecs'] );
    }
    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function get_number_of_repositories() {
        return count( $this->gedcom_objects['RepoRecs'] );
    }
    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function get_number_of_sources() {
        return count( $this->gedcom_objects['SrcRecs'] );
    }
    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function get_number_of_submitters() {
        return count( $this->gedcom_objects['SubmRecs'] );
    }
    /**
     * return the last update
     *
     * @access public
     * @return string
     */
    function get_last_update() {
        return $this->gedcom_objects['HeaderRec']->transmission_date_time;
    }
    /**
     * Get an Individual (object) FROM an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or boolean (error)
     */
    function get_individual( $identifier ) {
        if ( isset( $this->gedcom_objects['IndiRecs'][$identifier] ) )return $this->gedcom_objects['IndiRecs'][$identifier];
        else return null;
    }
    /**
     * Get a family (object) FROM an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or false on error.
     */
    function get_family( $identifier ) {
        if ( isset( $this->gedcom_objects['FamRecs'][$identifier] ) )return $this->gedcom_objects['FamRecs'][$identifier];
        else return null;
    }
    /**
     * Get an object (object) FROM an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or false on error.
     */
    function get_object( $identifier ) {
        if ( isset( $this->gedcom_objects['MediaRecs'][$identifier] ) )return $this->gedcom_objects['MediaRecs'][$identifier];
        else return null;
    }


    /**
     * @todo Description of function getSource
     * @param  $identifier
     * @return
     */
    function get_source( $identifier ) {
        if ( isset( $this->gedcom_objects['SrcRecs'][$identifier] ) )return $this->gedcom_objects['SrcRecs'][$identifier];
        else return null;
    }
    /**
     * Get the header object
     *
     *
     * @access public
     * @return mixed object or false on error.
     */
    function get_header() {
        return $this->gedcom_objects['HeaderRec'];
    }
    /**
     * test if an individual exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function is_individual( $identifier ) {
        return Array_key_exists( $identifier, $this->gedcom_objects['IndiRecs'] );
    }
    /**
     * test if a family exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function is_family( $identifier ) {
        return Array_key_exists( $identifier, $this->gedcom_objects['FamRecs'] );
    }
    /**
     * test if an object exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function is_object( $identifier ) {
        return Array_key_exists( $identifier, $this->gedcom_objects['MediaRecs'] );
    }
    /**
     *
     * @return string serialized gedcom file
     */
    function to_gedcom() {
        $ged = $this->gedcom_objects['HeaderRec']->to_gedcom( 0, '5.5.1' );
        $ged .= "\n" . $this->gedcom_objects['SubnRec']->to_gedcom( 0, '5.5.1' );
        $cnt = count( $this->gedcom_objects['IndiRecs'] );
        for ( $i = 0;
	$i < $cnt;
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['IndiRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        $cnt = count( $this->gedcom_objects['FamRecs'] );
        for ( $i = 0;
	$i < $cnt;
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['FamRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        for ( $i = 0;
	$i < count( $this->gedcom_objects['MediaRecs'] );
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['MediaRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        for ( $i = 0;
	$i < count( $this->gedcom_objects['NoteRecs'] );
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['NoteRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        for ( $i = 0;
	$i < count( $this->gedcom_objects['RepoRecs'] );
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['RepoRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        for ( $i = 0;
	$i < count( $this->gedcom_objects['SourRecs'] );
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['SourRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        for ( $i = 0;
	$i < count( $this->gedcom_objects['SubmRecs'] );
	$i++ ) {
            $ged .= "\n" . $this->gedcom_objects['SubmRecs'][$i]->to_gedcom( 0, '5.5.1' );
        }
        $ged .= "\n0 TRLR";
        return $ged;
    }
    /**
     * Creates a string representation of the object
     *
     * @return string serialized information about the parsed gedcom
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    function __toString() {
        $str = parent::__toString();
        $str .= "\nLast Update->" . $this->get_last_update();
        $str .= "\nIndividual Recs->" . $this->get_number_of_individuals();
        $str .= "\nFamily Recs->" . $this->get_number_of_families();
        $str .= "\nMedia Recs->" . $this->get_number_of_objects();
        $str .= "\nNote Recs->" . $this->get_number_of_notes();
        $str .= "\nRepository Recs->" . $this->get_number_of_repositories();
        $str .= "\nSource Recs->" . $this->get_number_of_sources();
        $str .= "\nSubmitter Recs->" . $this->get_number_of_submitters();
        $str .= "\n";
        return $str;
    }
    // TODO add getBirthdates function (with filter?)
    // TODO add getAnniversaries function (with filter?)

}
