<?php
/**
 * \Genealogy\Gedcom\Structures\EntityAbstract
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   SVN: $Id: EntityAbstract.php 297 2010-03-31 14:53:58Z ed4becky $
 * @link
 */
/**
 * EntityAbstract class RP_for Genealogy_Gedcom
 *                defines the parent class RP_for Structures and Records
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
abstract class RP_Entity_Abstract {
    /**
     * The version of the GEDCOM standard this object was populated from
     *
     * @var string
     */
    var $ver;
    /**
     * Restricts dynamic field creation
     *
     * @param string $dt dynamic field name
     * @param string $vl dynamic field value
     *
     * @return void
     *
     * @throws InvalidFieldException
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function _set( $dt, $vl ) {
        throw new RP_Invalid_Field_Exception( 'Cannot assign values to undefined variable:' . $dt );
    }
    /**
     * Restricts dynamic field retrieval
     *
     * @param string $dt dynamic field name
     *
     * @return void
     *
     * @throws InvalidFieldException
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function _get( $dt ) {
        throw new RP_Invalid_Field_Exception( 'Undefined variable: ' . $dt );
    }
    /**
     * Identifies the index into an array for a specific element
     *
     * @param string $tree the array to search within
     * @param string $tag  the tag to search for
     * @param int    $off  the offset into the $tree to begin the search
     *
     * @return int the index into the array
     * or false if not found
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    function find_tag( $tree, $tag, $off = 0 ) {
        $cnt = count( $tree );
        for ( $i = $off;
    $i < $cnt;
    $i++ ) {
            //            if(is_array($tree[$i][0])) {
            //                var_dump($tree[$i][0]);
            //            }
            if ( preg_match( '/^[0-9][0-9]? .*?' . $tag . '( |$)/US', $tree[$i][0] ) ) {
                return $i;
            }
        }
        return false;
    }
    /**
     * Extracts the text data portion of the record
     *
     * @param string $tree the array to search within
     * @param string $tag  the tag to search for
     *
     * @return string the data component of the record
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    protected function parse_text( $tree, $tag ) {
        $str = @preg_replace( '/^[0-9][0-9]? .*?' . $tag . ' (.*)/US', '$1', $tree[0] );
        if ( $str == $tree[0] ) {
            $str = '';
        }
        return $str;
    }
    /**
     * Extracts the id component of the record
     *
     * @param string $tree the array to sreach within
     * @param string $tag  the tag to search for
     *
     * @return string the id component of the record
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    protected function parse_ptr_id( $tree, $tag ) {
        $str = @preg_replace( '/^[0-9][0-9]? ' . $tag . ' @([A-Z, a-z, 0-9, :, !,-]*)@/US', '$1', $tree[0] );
        if ( $str == $tree[0] ) {
            $str = '';
        }
        return $str;
    }
    /**
     * Extracts the id component of the record
     *
     * @param string $tree the array to sreach within
     * @param string $tag  the tag to search for
     *
     * @return string the id component of the record
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    protected function parse_ref_id( $tree, $tag ) {
        $str = @preg_replace( '/^[0-9][0-9]? @([A-Z,a-z,0-9,:,!,-]*)@ ' . $tag . '(.*?)/US', '$1', $tree[0], 1 );
        if ( $str == $tree[0] ) {
            $str;
        }
        return $str;
    }
    /**
     * Extracts multiline text data FROM a record
     * with child continuation or concatentaion records
     *
     * @param string $tree the array to sreach within
     * @param string $tag  the parent tag to search for
     *
     * @return string the data component of the record
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    protected function parse_con_tag( $tree, $tag ) {
        $str = @preg_replace( '/^[0-9][0-9]? .*?' . $tag . ' ?(.*)/US', '$1', $tree[0], 1 );
        if ( isset( $tree[1] ) ) {
            $sub2 = $tree[1];
            $cnt = count( $sub2 );
            for ( $i = 0; $i < $cnt; $i++ ) {
                if ( @preg_match( '/^[0-9][0-9]? CONT ?(.*)/US', $sub2[$i][0] ) ) {
                    $str .= "\n" . @preg_replace( '/^[0-9][0-9]? CONT ?(.*)/US', '$1', $sub2[$i][0], 1 );
                } else if ( @preg_match( '/^[0-9][0-9]? CONC (.*)/US', $sub2[$i][0] ) ) {
                    $str .= @preg_replace( '/^[0-9][0-9]? CONC (.*)/US', '$1', $sub2[$i][0], 1 );
                }
            }
        }
        return $str;
    }
    /**
     * Creates multie record entires for a field
     * with child continuation or concatentaion records
     *
     * @param string $field   to break into multiple records
     * @param string $maintag the parent tag
     * @param string $mainLvl the parent level
     *
     * @return string the records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    protected function to_con_tag( $field, $maintag, $main_lvl ) {
        $ged_rec = '';
        // TODO don't break on a space
        $copy = explode( "\n", $field );
        if ( $maintag != null ) {
            $ged_rec .= $main_lvl . ' ' . $maintag . ' ';
        } else {
        }
        $ged_rec .= substr( $copy[0], 0, 90 );
        $lvlplus = $main_lvl + 1;
        //check for CONC of first line
        if ( Strlen( $copy[0] ) > 90 ) {
            $rem = 90;
            $len = Strlen( $copy[0] );
            while ( $rem < $len ) {
                $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CONC . ' ' . substr( $copy[0], $rem, 90 );
                $rem += 90;
            }
        }
        //check for CONC of each line
        //CONC before CONT since CONT contains the newline
        $cnt = count( $copy );
        for ( $i = 1;
    $i < $cnt;
    $i++ ) {
            $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CONT . ' ' . substr( $copy[$i], 0, 90 );
            if ( Strlen( $copy[$i] ) > 90 ) {
                $rem = 90;
                $len = Strlen( $copy[$i] );
                while ( $rem < $len ) {
                    $ged_rec .= "\n" . $lvlplus . ' ' . Rp_Tags::CONC . ' ' . substr( $copy[$i], $rem, 90 );
                    $rem += 90;
                }
            }
        }
        return $ged_rec;
    }
    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     * should be generated
     * @param string $ver represents the version of the GEDCOM standard
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function to_gedcom( $lvl, $ver ) {
        $ged_rec = '';
        return $ged_rec;
    }
    /**
     * Extracts attribute contents FROM a parent tree object
     *
     * @param array  $tree an array containing an array FROM which the
     * object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse_tree( $tree, $ver ) {
        $this->ver = $ver;
    }
    /**
     * Creates a string representation of the object
     *
     * @return string  contains string representation of each
     * public field in the object
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function __toString() {
        $str = __CLASS__;
        return $str;
    }
}
