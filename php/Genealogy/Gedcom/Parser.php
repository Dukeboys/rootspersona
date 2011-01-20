<?php
/**
 * \Genealogy\Gedcom\Parser
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Parser.php 303 2010-04-12 12:40:46Z ed4becky $
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */




/**
 * Main class for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/Genealogy_Gedcom
 */
class Parser
{
    /**
     * Name of the(to be) processed gedcom file
     *
     * @var string $gedcomFilename
     */
    var $gedcomFilename;

    /**
     * Version of the gedcom File
     * determiend after the Header has been parsed
     *
     * @var string $Ver
     */
    var $gedcomVersion;

    /**
     * Collection of 'base' objects
     * representing the records at a
     * depth of zero.
     *
     * @var array $gedComObjects
     */
    var $gedcomObjects= array(
    'HeaderRec' => '',
    'SubnRec' => '',
    'FamRecs' => array(),
    'IndiRecs' => array(),
    'MediaRecs' =>array(),
    'NoteRecs' => array(),
    'RepoRecs' => array(),
    'SrcRecs' => array(),
    'SubmRecs' => array()
    );

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
    public function parse($Filename)
    {
        $this->gedcomFilename = $Filename;
        $buffer = array();
        if ($fp = fopen($this->gedcomFilename, 'r')) {
            while (!feof($fp)) {
                $line = trim(fgets($fp, 1024));
                if ($line != '') {
                    $buffer[] = $line;
                }
            }
            fclose($fp);
        } else {
            throw new FileException(
                'Cannot open file '.$this->gedcomFilename
            );
        }
        $this->_validate($buffer);
        $gedcomTree = array();
        $this->_createTree($buffer, 0, 0, $gedcomTree);
        $this->_parseTree($gedcomTree);
    }

    /**
     * Validate that the file is a valid gedcom file
     *
     * @param array &$buffer lines of the gedcom file
     *
     * @return void
     * @throws FileException
     * @access private
     * @since Method available since Release 0.0.1
     */
    private function _validate(&$buffer)
    {
        if (($buffer[0] != '0 HEAD')
            ||($buffer[count($buffer) -1] != '0 TRLR')
        ) {
            throw new FileException(
                'Invalid GEDCOM file: invalid HEAD or TRLR: '
                . $this->gedcomFilename
            );
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
     * @param array &$records contains lines being parsed
     * @param int	$idx      indicates current offset to be parsed in $records
     * @param int	$depth    indicates current depth to be parsed
     * @param array &$tree    empty container for records to be parsed
     *                        at current depth for the current parent
     *
     * @return int  $idx      index of last record created
     */
    private function _createTree(&$records, $idx, $depth, &$tree)
    {
        // the first call is always at the right depth.
        $tree[] = array($records[$idx]);
        $idx++;
        while ($idx < count($records)) {
            // depth indicator can only be(0-99)
            // so we grab 2 digits or 1 digit and strip space
            $recDepth = trim(substr($records[$idx], 0, 2));
            if ($recDepth < $depth) {
                // need to go back to the recursive parent
                break;
            } else if ($recDepth > $depth) {
                // need to recurse forward
                $off = sizeof($tree) - 1;
                // will need an array for the next lvl
                $tree[$off][1] = array();
                $idx = $this->_createTree(&$records, $idx, $recDepth, $tree[$off][1]);
            } else {
                //stay here for another round
                $tree[] = array($records[$idx]);
                $idx++;
            }
        }
        // each recursive parent will test and find eof
        return $idx;
    }

    /**
     * Creates objects from tree nodes
     *
     * @param array &$tree the populated tree of records
     *
     * @return void
     */
    private function _parseTree(&$tree)
    {
        foreach ($tree as $row) {
            if (@preg_match('/0 HEAD/US', $row[0])) {
                $this->_parseHeader($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ SUBN/US', $row[0])) {
                $this->_parseSubmission($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ FAM/US', $row[0])) {
                $this->_parseFamily($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ INDI/US', $row[0])) {
                $this->_parseIndividual($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ OBJE/US', $row[0])) {
                $this->_parseMedia($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ NOTE/US', $row[0])) {
                $this->_parseNote($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ REPO/US', $row[0])) {
                $this->_parseRespository($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ SOUR/US', $row[0])) {
                $this->_parseSource($row);
            } else if (@preg_match('/0 @[A-Z, a-z, 0-9, :, !]*@ SUBM/US', $row[0])) {
                $this->_parseSubmitter($row);
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
    private function _parseHeader($tree)
    {
        $rec = new HeaderRecord();
        $rec->parseTree(array($tree), null);
        $this->gedcomObjects['HeaderRec'] = $rec;
        $this->gedcomVersion = $rec->GedC->VerNbr;
    }

    /**
     * Creates the Submission Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseSubmission($tree)
    {
        $rec = new SubmissionRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['SubnRec'] = $rec;
    }
    /**
     * Creates the Family Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseFamily($tree)
    {
        $rec = new FamilyRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['FamRecs']["$rec->Id"] = $rec;
    }
    /**
     * Creates the Individual Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseIndividual($tree)
    {
        $rec = new IndividualRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['IndiRecs']["$rec->Id"] = $rec;
    }

    /**
     * Creates the Media Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseMedia($tree)
    {
        $rec = new MediaRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['MediaRecs']["$rec->Id"] = $rec;
    }

    /**
     * Creates the Note Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseNote($tree)
    {
        $rec = new NoteRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['NoteRecs']["$rec->Id"] = $rec;
    }
    /**
     * Creates the Repository Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseRespository($tree)
    {
        $rec = new RepositoryRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['RepoRecs']["$rec->Id"] = $rec;
    }
    /**
     * Creates the Source Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseSource($tree)
    {
        $rec = new SourceRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['SrcRecs']["$rec->Id"] = $rec;
    }

    /**
     * Creates the Submitter Object
     *
     * @param array $tree hierarchical record data
     *
     * @return void
     */
    private function _parseSubmitter($tree)
    {
        $rec = new SubmitterRecord();
        $rec->parseTree(array($tree), $this->gedcomVersion);
        $this->gedcomObjects['SubmRecs']["$rec->Id"] = $rec;
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
    function __toString()
    {
        $str = 'Gedcom Filename->' . basename($this->gedcomFilename);
        $str .= "\nGedcom Version->" . $this->gedcomVersion;
        return $str;
    }
}
