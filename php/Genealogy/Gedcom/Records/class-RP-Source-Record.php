<?php
/**
 * \Genealogy\Gedcom\Records\SourceRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SourceRecord.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * SourceRecord class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Source_Record extends RP_Record_Abstract {
	/**
	 *
	 * @var string
	 */
	var $id;
	/**
	 *
	 * @var SourceData
	 */
	var $source_data = array();
	/**
	 *
	 * @var string
	 */
	var $author;
	/**
	 *
	 * @var string
	 */
	var $title;
	/**
	 *
	 * @var string
	 */
	var $abbreviated_title;
	/**
	 *
	 * @var string
	 */
	var $publication_facts;
	/**
	 *
	 * @var string
	 */
	var $text;
	/**
	 *
	 * @var array
	 */
	var $repository_citations = array();
	/**
	 *
	 * @var array
	 */
	var $media_links = array();
	/**
	 *
	 * @var array
	 */
	var $notes = array();
	/**
	 *
	 * @var array
	 */
	var $user_ref_nbrs = array();
	/**
	 *
	 * @var string
	 */
	var $auto_rec_id;
	/**
	 *
	 * @var string
	 */
	var $change_date;
	/**
	 * Initializes complex attributes
	 *
	 * @return none
	 */
	public function __construct() {
		$this->change_date = new RP_Change_Date();
	}
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
		$ged_rec = '';
		if ( ! isset( $ver )
		|| $ver === '' ) {
			$ver = $this->ver;
		}
		if ( strpos( $ver, '5.5.1' ) == 0 ) {
			$ged_rec = $lvl . ' @' . $this->id . '@ ' . Rp_Tags::SOURCE;
			$lvl2 = $lvl + 1;
			for ( $i = 0;
	$i < count( $this->source_data );
	$i++ ) {
				$ged_rec .= "\n" . $this->source_data[$i]->to_gedcom( $lvl2, $ver );
			}
			if ( isset( $this->author )
			&& $this->author != '' ) {
				$ged_rec .= "\n" . parent::to_con_tag( $this->author, Rp_Tags::AUTHOR, $lvl2 );
			}
			if ( isset( $this->title )
			&& $this->title != '' ) {
				$ged_rec .= "\n" . parent::to_con_tag( $this->title, Rp_Tags::TITLE, $lvl2 );
			}
			if ( isset( $this->abbreviated_title )
			&& $this->abbreviated_title != '' ) {
				$ged_rec .= "\n" . parent::to_con_tag( $this->abbreviated_title, Rp_Tags::ABBR, $lvl2 );
			}
			if ( isset( $this->publication_facts )
			&& $this->publication_facts != '' ) {
				$ged_rec .= "\n" . parent::to_con_tag( $this->publication_facts, Rp_Tags::PUBLICATION, $lvl2 );
			}
			if ( isset( $this->text )
			&& $this->text != '' ) {
				$ged_rec .= "\n" . parent::to_con_tag( $this->text, Rp_Tags::TEXT, $lvl2 );
			}
			for ( $i = 0;
	$i < count( $this->repository_citations );
	$i++ ) {
				$ged_rec .= "\n" . $this->repository_citations[$i]->to_gedcom( $lvl2, $ver );
			}
			$ged_rec = $this->user_ref_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->change_date_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->note_to_gedcom( $ged_rec, $lvl2, $ver );
			$ged_rec = $this->media_to_gedcom( $ged_rec, $lvl2, $ver );
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
		$this->id = parent::parse_ref_id( $tree[0], Rp_Tags::SOURCE );
		if ( isset( $tree[0][1] ) ) {
			$sub2 = $tree[0][1];
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::DATA, $off ) ) !== false ) {
				$tmp = new RP_Source_Data();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->source_data[] = $tmp;
                $off = $i1 + 1;
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::AUTHOR ) ) !== false ) {
				$this->author = parent::parse_con_tag( $sub2[$i1], Rp_Tags::AUTHOR );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::TITLE ) ) !== false ) {
				$this->title = parent::parse_con_tag( $sub2[$i1], Rp_Tags::TITLE );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::ABBR ) ) !== false ) {
				$this->abbreviated_title = parent::parse_con_tag( $sub2[$i1], Rp_Tags::ABBR );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::PUBLICATION ) ) !== false ) {
				$this->publication_facts = parent::parse_con_tag( $sub2[$i1], Rp_Tags::PUBLICATION );
			}
			if ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::TEXT ) ) !== false ) {
				$this->text = parent::parse_con_tag( $sub2[$i1], Rp_Tags::TEXT );
			}
			$off = 0;
			while ( ( $i1 = parent::find_tag( $sub2, Rp_Tags::REPOSITORY, $off ) ) !== false ) {
				$tmp = new RP_Repository_Citation();
				$tmp->parse_tree( array( $sub2[$i1] ), $ver );
				$this->repository_citations[] = $tmp;
                $off = $i1 + 1;
			}
			$this->user_file_parse_tree( $sub2, $ver );
			$this->auto_rec_parse_tree( $sub2, $ver );
			$this->change_date_parse_tree( $sub2, $ver );
			$this->media_parse_tree( $sub2, $ver );
			$this->note_parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(Id->' . $this->id . ', Author->' . $this->author . ', Title->' . $this->title . ', AbbreviatedTitle->' . $this->abbreviated_title . ', PublicationFacts->' . $this->publication_facts . ', Text->' . $this->text . ', SourceData->(';
		for ( $i = 0;
	$i < count( $this->source_data );
	$i++ ) {
			$str .= "\n" . $this->source_data[$i];
		}
		$str .= '), SourceRepositoryCitations->(';
		for ( $i = 0;
	$i < count( $this->repository_citations );
	$i++ ) {
			$str .= "\n" . $this->repository_citations[$i];
		}
		$str .= '), UserRefNbrs->(';
		for ( $i = 0;
	$i < count( $this->user_ref_nbrs );
	$i++ ) {
			$str .= 'UserRefNbr->' . $this->user_ref_nbrs[$i]['Nbr'] . ' (' . $this->user_ref_nbrs[$i]['Type'] . ')';
		}
		$str .= '), AutoRecId->' . $this->auto_rec_id . ', ChangeDate->' . $this->change_date . ', MediaLinks->(';
		for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
			$str .= "\n" . $this->media_links[$i];
		}
		$str .= '), Notes->(';
		for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
			$str .= "\n" . $this->notes[$i];
		}
		$str .= '))';
		return $str;
	}
}
?>
