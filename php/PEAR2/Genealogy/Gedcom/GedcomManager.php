<?php
/**
 * PEAR2\Genealogy\Gedcom\GedcomManager
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: GedcomManager.php 303 2010-04-12 12:40:46Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom;

/**
 * Main class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
class GedcomManager extends Parser
{
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
    function parse($Filename)
    {
        parent::parse($Filename);
    }

    /**
     * return the number of individual
     *
     * @access public
     * @return integer
     */
    function getNumberOfIndividuals()
    {
        return count($this->gedcomObjects['IndiRecs']);
    }

    /**
     * return the number of family
     *
     * @access public
     * @return integer
     */
    function getNumberOfFamilies()
    {
        return count($this->gedcomObjects['FamRecs']);
    }

    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function getNumberOfObjects()
    {
        return count($this->gedcomObjects['MediaRecs']);
    }

    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function getNumberOfNotes()
    {
        return count($this->gedcomObjects['NoteRecs']);
    }

    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function getNumberOfRepositories()
    {
        return count($this->gedcomObjects['RepoRecs']);
    }

    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function getNumberOfSources()
    {
        return count($this->gedcomObjects['SrcRecs']);
    }

    /**
     * return the number of object
     *
     * @access public
     * @return integer
     */
    function getNumberOfSubmitters()
    {
        return count($this->gedcomObjects['SubmRecs']);
    }
    /**
     * return the last update
     *
     * @access public
     * @return string
     */
    function getLastUpdate()
    {
        return $this->gedcomObjects['HeaderRec']->TransmissionDateTime;
    }

    /**
     * Get an Individual (object) from an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or boolean (error)
     */
    function getIndividual($identifier)
    {
        return $this->gedcomObjects['IndiRecs'][$identifier];
    }

    /**
     * Get a family (object) from an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or false on error.
     */
    function getFamily($identifier)
    {
        return $this->gedcomObjects['FamRecs'][$identifier];
    }

    /**
     * Get an object (object) from an identifier
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return mixed object or false on error.
     */
    function getObject($identifier)
    {
        return $this->gedcomObjects['MediaRecs'][$identifier];
    }

    /**
     * Get the header object
     *
     *
     * @access public
     * @return mixed object or false on error.
     */
    function getHeader()
    {
        return $this->gedcomObjects['HeaderRec'];
    }
    /**
     * test if an individual exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function isIndividual($identifier)
    {
        return array_key_exists($identifier, $this->gedcomObjects['IndiRecs']);
    }

    /**
     * test if a family exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function isFamily($identifier)
    {
        return array_key_exists($identifier, $this->gedcomObjects['FamRecs']);
    }

    /**
     * test if an object exists
     *
     * @param string $identifier Identifier
     *
     * @access public
     * @return boolean
     */
    function isObject($identifier)
    {
        return array_key_exists($identifier, $this->gedcomObjects['MediaRecs']);
    }

    /**
     *
     * @return string serialized gedcom file
     */
    function toGedcom()
    {
        $ged = $this->gedcomObjects['HeaderRec']->toGedcom(0, '5.5.1');

        $ged .= "\n"
        . $this->gedcomObjects['SubnRec']->toGedcom(0, '5.5.1');

        for ($i = 0; $i < count($this->gedcomObjects['IndiRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['IndiRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['FamRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['FamRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['MediaRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['MediaRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['NoteRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['NoteRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['RepoRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['RepoRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['SourRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['SourRecs'][$i]->toGedcom(0, '5.5.1');
        }

        for ($i = 0; $i < count($this->gedcomObjects['SubmRecs']); $i++) {
            $ged .= "\n"
            . $this->gedcomObjects['SubmRecs'][$i]->toGedcom(0, '5.5.1');
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
    function __toString()
    {
        $str = parent::__toString();
        $str .= "\nLast Update->" . $this->getLastUpdate();
        $str .= "\nIndividual Recs->" . $this->getNumberOfIndividuals();
        $str .= "\nFamily Recs->" . $this->getNumberOfFamilies();
        $str .= "\nMedia Recs->" . $this->getNumberOfObjects();
        $str .= "\nNote Recs->" . $this->getNumberOfNotes();
        $str .= "\nRepository Recs->" . $this->getNumberOfRepositories();
        $str .= "\nSource Recs->" . $this->getNumberOfSources();
        $str .= "\nSubmitter Recs->" . $this->getNumberOfSubmitters();
        $str .= "\n";
        return $str;
    }

    // TODO add getBirthdates function (with filter?)
    // TODO add getAnniversaries function (with filter?)
}
