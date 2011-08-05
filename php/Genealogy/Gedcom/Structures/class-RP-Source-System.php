<?php
/**
 * \Genealogy\Gedcom\SourceSystem
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SourceSystem.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link
 */
/**
 * SourceSystem class RP_for Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link
 */
class RP_Source_System extends RP_Entity_Abstract {
	/**
	 *
	 * @var string
	 */
	var $system_id;
	/**
	 *
	 * @var string
	 */
	var $ver_nbr;
	/**
	 *
	 * @var unknown_type
	 */
	var $product_name;
	/**
	 *
	 * @var Corporation
	 */
	var $corporation;
	/**
	 *
	 * @var rpData
	 */
	var $rp_data;
	/**
	 * constructor
	 */
	function __construct() {
		$this->corporation = new RP_Corporation();
		$this->rp_data = new RP_Data();
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
		if ( isset( $this->system_id )
		&& $this->system_id != '' ) {
			$ged_rec .= $lvl . ' ' . Rp_Tags::SOURCE . ' ' . $this->system_id;
		}
		$lvl2 = $lvl + 1;
		if ( isset( $this->ver_nbr )
		&& $this->ver_nbr != '' ) {
			$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::VERSION . ' ' . $this->ver_nbr;
		}
		if ( isset( $this->product_name )
		&& $this->product_name != '' ) {
			$ged_rec .= "\n" . $lvl2 . ' ' . Rp_Tags::NAME . ' ' . $this->product_name;
		}
		$str = $this->corporation->to_gedcom( $lvl2, null );
		if ( isset( $str )
		&& $str != '' ) {
			$ged_rec .= "\n" . $str;
		}
		$str = $this->rp_data->to_gedcom( $lvl2, null );
		if ( isset( $str )
		&& $str != '' ) {
			$ged_rec .= "\n" . $str;
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
		if ( ( $i1 = parent::find_tag( $tree, Rp_Tags::SOURCE ) ) !== false ) {
			$this->system_id = parent::parse_text( $tree[$i1], Rp_Tags::SOURCE );
			if ( isset( $tree[$i1][1] ) ) {
				$sub2 = $tree[$i1][1];
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::VERSION ) ) !== false ) {
					$this->ver_nbr = parent::parse_text( $sub2[$i2], Rp_Tags::VERSION );
				}
				if ( ( $i2 = parent::find_tag( $sub2, Rp_Tags::NAME ) ) !== false ) {
					$this->product_name = parent::parse_text( $sub2[$i2], Rp_Tags::NAME );
				}
				$this->corporation->parse_tree( $sub2, $ver );
				$this->rp_data->parse_tree( $sub2, $ver );
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
		$str = __CLASS__ . '(SystemId->' . $this->system_id . ', VerNbr->' . $this->ver_nbr . ', ProductName->' . $this->product_name . ', Corporation->' . $this->corporation . ', rpData->' . $this->rp_data . ')';
		return $str;
	}
}
