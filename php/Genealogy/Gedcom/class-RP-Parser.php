<?php
/**
 * \Genealogy\Gedcom\rpParser
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Parser.php 303 2010-04-12 12:40:46Z ed4becky $
 * @link
 */
//require_once 'RP_Ansel2Unicode.php';
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
class RP_Parser {
    /**
     * Name of the(to be) processed gedcom file
     *
     * @var string $gedcomFilename
     */
    var $gedcom_filename;
    /**
     * Version of the gedcom File
     * determiend after the Header has been parsed
     *
     * @var string $Ver
     */
    var $gedcom_version;

    var $char_set = 'ANSEL';
    /**
     * Collection of 'base' objects
     * representing the records at a
     * depth of zero.
     *
     * @var array $gedComObjects
     */
    var $gedcom_objects = array( 'HeaderRec' => '', 'SubnRec' => '', 'FamRecs' => array(), 'IndiRecs' => array(), 'MediaRecs' => array(), 'NoteRecs' => array(), 'RepoRecs' => array(), 'SrcRecs' => array(), 'SubmRecs' => array() );
    var $ansel_converter = null;
    /**
     * Reads a file into an array.
     * Validates the contents of the file.
     * Parses the records into a tree of nodes.
     * Parses the tree into objects.
     *
     * @param string $Filename name of file to be parsed
     *
     * @return void
     * @throws FileException
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parse( $_filename, $callback_class = null ) {
        @ini_set('auto_detect_line_endings',true);
        $this->gedcom_filename = $_filename;
        $callback = ( $callback_class === null ? $this : $callback_class );
        $line = null;
        try {
            if ( ($fp = fopen( $this->gedcom_filename, 'r' )) !== false ) {
                $buffer = array();
                $next_line = $this->import_header( $fp, array( $callback, 'process_header' ) );
                while ( true ) {
                    $buffer[] = $this->convert_to_utf8( $next_line );
                    while ( ! feof( $fp ) ) {
                        $line = trim( fgets( $fp, 1024 ) );
                        if ( $line != ''
                        && $line[0] != '0' ) {
                            $buffer[] = $this->convert_to_utf8( $line );
                        } else {
                            $gedcom_tree = null;
                            $this->create_tree( $buffer, 0, 0, $gedcom_tree );
                            $this->parse_tree( $gedcom_tree, $callback );
                            unset( $buffer );
                            unset( $gedcom_tree );
                            set_time_limit( 60 );
                            $next_line = $line;
                            break;
                        }
                    }
                    if ( $line === null )$line = $next_line;if ( strpos( $line, '0 TRLR' ) !== false ) {
                        fclose( $fp );
                        break;
                    } else if ( feof( $fp ) ) {
                        fclose( $fp );
                        throw new RP_File_Exception( 'Invalid GEDCOM file: invalid TRLR record.' );
                    }
                }
            } else {
                throw new RP_File_Exception( 'Cannot open file ' . $this->gedcom_filename );
            }
        } catch ( Exception $e ) {
            //if(isset($fp)) fclose($fp);
            throw $e;
        }
    }


    /**
     * @todo Description of function importHeader
     * @param  & $fp
     * @param  $processHeader
     * @return
     */
    function import_header( &$fp, $process_header ) {
        if ( feof( $fp ) ) {
            throw new RP_File_Exception( 'Invalid GEDCOM file: invalid HEAD record.' );
        }
        $line = trim( fgets( $fp, 1024 ) );
        if ( ( $line != '' )
        && ( strpos( $line, '0 HEAD' ) !== false ) ) {
            $buffer[] = $line;
            while ( ! feof( $fp ) ) {
                $line = trim( fgets( $fp, 1024 ) );
                if ( $line[0] == '0' ) {
                    break;
                } else if ( $line != ''
                && $line[0] != '0' ) {
                    $buffer[] = $line;
                }
            }
            $gedcom_tree = null;
            $this->create_tree( $buffer, 0, 0, $gedcom_tree );
            $this->parse_header( $gedcom_tree, $process_header );
        } else {
            throw new RP_File_Exception( 'Invalid GEDCOM file: invalid HEAD record.' );
        }
        return $line;
    }


    /**
     * @todo Description of function convertToUTF8
     * @param  $str
     * @return
     */
    function convert_to_utf8( $str ) {
        switch ( $this->char_set ) {
            case 'UTF8':
            case 'UTF-8':
                return $str;
            case 'ANSEL':
                if ( $this->ansel_converter === null )$this->ansel_converter = new RP_Ansel2_Unicode();
                return $this->ansel_converter->convert( $str );
            case 'ANSI':
                return mb_convert_encoding( $str, 'UTF-8', 'CP1252' );
            case 'ASCII':
                return mb_convert_encoding( $str, 'UTF-8', 'ASCII' );
            case 'ISO-8859-1':
                return mb_convert_encoding( $str, 'UTF-8', 'ISO-8859-1' );
            case 'IBMPC':
            case 'IBM WINDOWS':
                // Note:The IBMPC character set is not allowed.
                // This character set cannot be interpreted properly
                // without knowing which code page the sender was using.
                // we will fall thru to default and hope for the best

            default:
                return mb_convert_encoding( $str, 'UTF-8' );
            }
    }
    /**
     * Creates a tree of nodes.  Designed for recursion.
     *
     * Each node is an array.
     *
     * A branch node is a two dimensional array
     * consisting of a string and an array of one
     * or more child nodes.
     *
     * A leaf node is a one dimensional array
     * consisting of a string.
     *
     * By making the leaf a node(array),
     * it can be turned into a branch if needed.
     *
     * @param array $records contains lines being parsed
     * @param int    $idx      indicates current offset to be parsed in $records
     * @param int    $depth    indicates current depth to be parsed
     * @param array $tree    empty container for records to be parsed
     *                        at current depth for the current parent
     *
     * @return int  $idx      index of last record created
     */
    private function create_tree( $records, $idx, $depth, &$tree ) {
        // the first call is always at the right depth.
        $tree[] = array( $records[$idx] );
        $idx++;
        $cnt = count( $records );
        while ( $idx < $cnt ) {
            // depth indicator can only be(0-99)
            // so we grab 2 digits or 1 digit and strip space
            $rec_depth = trim( substr( $records[$idx], 0, 2 ) );
            if ( $rec_depth < $depth ) {
                // need to go back to the recursive parent
                break;
            } else if ( $rec_depth > $depth ) {
                // need to recurse forward
                $off = sizeof( $tree ) - 1;
                // will need an array for the next lvl
                $tree[$off][1] = array();
                $idx = $this->create_tree( $records, $idx, $rec_depth, $tree[$off][1] );
            } else {
                //stay here for another round
                $tree[] = array( $records[$idx] );
                $idx++;
            }
        }
        // each recursive parent will test and find eof
        return $idx;
    }
    /**
     * Creates objects FROM tree nodes
     *
     * @param array $tree the populated tree of records
     *
     * @return void
     */
    private function parse_tree( $tree, $callback ) {
        foreach ( $tree as $row ) {
            if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ SUBN/US', $row[0] ) ) {
                $this->parse_submission( $row, array( $callback, 'process_submission' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ FAM/US', $row[0] ) ) {
                $this->parse_family( $row, array( $callback, 'process_family' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ INDI/US', $row[0] ) ) {
                $this->parse_individual( $row, array( $callback, 'process_individual' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ OBJE/US', $row[0] ) ) {
                $this->parse_media( $row, array( $callback, 'process_media' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ NOTE/US', $row[0] ) ) {
                $this->parse_note( $row, array( $callback, 'process_note' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ REPO/US', $row[0] ) ) {
                $this->parse_repository( $row, array( $callback, 'process_repository' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ SOUR/US', $row[0] ) ) {
                $this->parse_source( $row, array( $callback, 'process_source' ) );
            } else if ( @preg_match( '/0 @[A-Z, a-z, 0-9, :, !, -]*@ SUBM/US', $row[0] ) ) {
                $this->parse_submitter( $row, array( $callback, 'process_submitter' ) );
            }
        }
    }
    /**
     * Creates the Header Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_header( $tree, $process_header ) {
        $rec = new RP_Header_Record();
        $rec->parse_tree( $tree, null );
        $this->gedcom_version = $rec->ged_c->ver_nbr;
        $this->char_set = $rec->character_set;
        call_user_func( $process_header, $rec );
    }


    /**
     * @todo Description of function processHeader
     * @param  $rec
     * @return
     */
    function process_header( $rec ) {
        $this->gedcom_objects['HeaderRec'] = $rec;
    }
    /**
     * Creates the Submission Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_submission( $tree, $process_submission ) {
        $rec = new RP_Submission_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_submission, $rec );
    }


    /**
     * @todo Description of function processSubmission
     * @param  $rec
     * @return
     */
    function process_submission( $rec ) {
        $this->gedcom_objects['SubnRec'] = $rec;
    }
    /**
     * Creates the Family Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_family( $tree, $process_family ) {
        $rec = new RP_Family_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_family, $rec );
    }


    /**
     * @todo Description of function processFamily
     * @param  $rec
     * @return
     */
    function process_family( $rec ) {
        $this->gedcom_objects['FamRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Individual Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_individual( $tree, $process_individual ) {
        $rec = new RP_Individual_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_individual, $rec );
    }


    /**
     * @todo Description of function processIndividual
     * @param  $rec
     * @return
     */
    function process_individual( $rec ) {
        $this->gedcom_objects['IndiRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Media Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_media( $tree, $process_media ) {
        $rec = new RP_Media_Record( $process_media );
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_media, $rec );
    }


    /**
     * @todo Description of function processMedia
     * @param  $rec
     * @return
     */
    function process_media( $rec ) {
        $this->gedcom_objects['MediaRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Note Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_note( $tree, $process_note ) {
        $rec = new RP_Note_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_note, $rec );
    }


    /**
     * @todo Description of function processNote
     * @param  $rec
     * @return
     */
    function process_note( $rec ) {
        $this->gedcom_objects['NoteRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Repository Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_repository( $tree, $process_repository ) {
        $rec = new RP_Repository_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_repository, $rec );
    }


    /**
     * @todo Description of function processRepository
     * @param  $rec
     * @return
     */
    function process_repository( $rec ) {
        $this->gedcom_objects['RepoRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Source Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_source( $tree, $process_source ) {
        $rec = new RP_Source_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_source, $rec );
    }


    /**
     * @todo Description of function processSource
     * @param  $rec
     * @return
     */
    function process_source( $rec ) {
        $this->gedcom_objects['SrcRecs']["$rec->id"] = $rec;
    }
    /**
     * Creates the Submitter Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function parse_submitter( $tree, $process_submitter ) {
        $rec = new RP_Submitter_Record();
        $rec->parse_tree( array( $tree ), $this->gedcom_version );
        call_user_func( $process_submitter, $rec );
    }


    /**
     * @todo Description of function processSubmitter
     * @param  $rec
     * @return
     */
    function process_submitter( $rec ) {
        $this->gedcom_objects['SubmRecs']["$rec->id"] = $rec;
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
    function __toString() {
        $str = 'Gedcom Filename->' . Basename( $this->gedcom_filename );
        $str .= "\nGedcom Version->" . $this->gedcom_version;
        return $str;
    }
}
