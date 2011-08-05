<?php
/**
 * \Genealogy\Gedcom\Records\RecordAbstract
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version   SVN: $Id: EntityAbstract.php 297 2010-03-31 14:53:58Z ed4becky $
 * @link
 */
/**
 * RecordsAbstract class RP_for Genealogy_Gedcom
 *                defines the parent class RP_for Records
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
abstract class RP_Record_Abstract extends RP_Entity_Abstract {
	

	/**
	 * @todo Description of function userRefToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function user_ref_to_gedcom( $ged_rec, $level, $ver ) {
		for ( $i = 0;
	$i < count( $this->user_ref_nbrs );
	$i++ ) {
			$ged_rec .= "\n" . $level . ' ' . Rp_Tags::USERFILE . ' ' . $this->user_ref_nbrs[$i]['Nbr'];
			if ( isset( $this->user_ref_nbrs[$i]['Type'] ) ) {
				$ged_rec .= "\n" . ( $level + 1 ) . ' ' . Rp_Tags::TYPE . ' ' . $this->user_ref_nbrs[$i]['Type'];
			}
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function autoRecToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function auto_rec_to_gedcom( $ged_rec, $level, $ver ) {
		if ( isset( $this->auto_rec_id )
		&& $this->auto_rec_id != '' ) {
			$ged_rec .= "\n" . $level . ' ' . Rp_Tags::AUTORECID . ' ' . $this->auto_rec_id;
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function changeDateToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function change_date_to_gedcom( $ged_rec, $level, $ver ) {
		$tmp = $this->change_date->to_gedcom( $level, $ver );
		if ( isset( $tmp )
		&& $tmp != '' ) {
			$ged_rec .= "\n" . $tmp;
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function citeToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function cite_to_gedcom( $ged_rec, $level, $ver ) {
		for ( $i = 0;
	$i < count( $this->citations );
	$i++ ) {
			$ged_rec .= "\n" . $this->citations[$i]->to_gedcom( $level, $ver );
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function mediaToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function media_to_gedcom( $ged_rec, $level, $ver ) {
		for ( $i = 0;
	$i < count( $this->media_links );
	$i++ ) {
			$ged_rec .= "\n" . $this->media_links[$i]->to_gedcom( $level, $ver );
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function noteToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function note_to_gedcom( $ged_rec, $level, $ver ) {
		for ( $i = 0;
	$i < count( $this->notes );
	$i++ ) {
			$ged_rec .= "\n" . $this->notes[$i]->to_gedcom( $level, $ver );
		}
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function commonToGedcom
	 * @param  $gedRec 
	 * @param  $level 
	 * @param  $ver
	 * @return 
	 */
	public function common_to_gedcom( $ged_rec, $level, $ver ) {
		$ged_rec = $this->user_ref_to_gedcom( $ged_rec, $level, $ver );
		$ged_rec = $this->auto_rec_to_gedcom( $ged_rec, $level, $ver );
		$ged_rec = $this->change_date_to_gedcom( $ged_rec, $level, $ver );
		$ged_rec = $this->cite_to_gedcom( $ged_rec, $level, $ver );
		$ged_rec = $this->media_to_gedcom( $ged_rec, $level, $ver );
		$ged_rec = $this->note_to_gedcom( $ged_rec, $level, $ver );
		return $ged_rec;
	}
	

	/**
	 * @todo Description of function userFileParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function user_file_parse_tree( $sub_tree, $ver ) {
		if ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::USERFILE ) ) !== false ) {
			$this->user_ref_nbrs[]['Nbr'] = parent::parse_text( $sub_tree[$i1], Rp_Tags::USERFILE );
			if ( isset( $sub_tree[$i1][1] ) ) {
				if ( ( $i2 = parent::find_tag( $sub_tree[$i1][1], Rp_Tags::TYPE ) ) !== false ) {
					$this->user_ref_nbrs[count( $this->user_ref_nbrs ) - 1]['Type'] = parent::parse_text( $sub_tree[$i1][1][$i2], Rp_Tags::TYPE );
				}
			}
		}
	}
	

	/**
	 * @todo Description of function autoRecParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function auto_rec_parse_tree( $sub_tree, $ver ) {
		if ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::AUTORECID ) ) !== false ) {
			$this->auto_rec_id = parent::parse_text( $sub_tree[$i1], Rp_Tags::AUTORECID );
		}
	}
	

	/**
	 * @todo Description of function changeDateParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function change_date_parse_tree( $sub_tree, $ver ) {
		if ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::CHANGEDATE ) ) !== false ) {
			$this->change_date->parse_tree( array( $sub_tree[$i1] ), $ver );
		}
	}
	

	/**
	 * @todo Description of function citeParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function cite_parse_tree( $sub_tree, $ver ) {
		$off = 0;
		while ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::CITE, $off ) ) !== false ) {
			$tmp = new RP_Citation();
			$tmp->parse_tree( array( $sub_tree[$i1] ), $ver );
			$this->citations[] = $tmp;
	$off = $i1 + 1;
		}
	}
	

	/**
	 * @todo Description of function mediaParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function media_parse_tree( $sub_tree, $ver ) {
		$off = 0;
		while ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::MEDIA, $off ) ) !== false ) {
			$tmp = new RP_Media_Link();
			$tmp->parse_tree( array( $sub_tree[$i1] ), $ver );
			$this->media_links[] = $tmp;
	$off = $i1 + 1;
		}
	}
	

	/**
	 * @todo Description of function noteParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function note_parse_tree( $sub_tree, $ver ) {
		$off = 0;
		while ( ( $i1 = parent::find_tag( $sub_tree, Rp_Tags::NOTE, $off ) ) !== false ) {
			$tmp = new RP_Note();
			$tmp->parse_tree( array( $sub_tree[$i1] ), $ver );
			$this->notes[] = $tmp;
	$off = $i1 + 1;
		}
	}
	

	/**
	 * @todo Description of function commonParseTree
	 * @param  $subTree 
	 * @param  $ver
	 * @return 
	 */
	public function common_parse_tree( $sub_tree, $ver ) {
		$this->user_file_parse_tree( $sub_tree, $ver );
		$this->auto_rec_parse_tree( $sub_tree, $ver );
		$this->change_date_parse_tree( $sub_tree, $ver );
		$this->cite_parse_tree( $sub_tree, $ver );
		$this->media_parse_tree( $sub_tree, $ver );
		$this->note_parse_tree( $sub_tree, $ver );
	}
}
