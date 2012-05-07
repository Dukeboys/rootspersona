<?php


/**
 * @todo Description of class RP_RpSimplePerson
 * @author ed4becky
 * @copyright 2010-2011  Ed Thompson  (email : ed@ed4becky.org)
 * @version 2.0.x
 * @package rootspersona_php
 * @subpackage
 * @category rootspersona
 * @link www.ed4becky.net
 * @since 2.0.0
 * @license http://www.opensource.org/licenses/GPL-2.0
 */
class RP_Simple_Person {
	var $id;
	var $batch_id;
	var $full_name;
	var $surname= "";
	var $given = "";
	var $birth_date;
	var $death_date;
	var $father;
	var $f_persona;
	var $mother;
	var $m_persona;
	var $famc;
	var $fams = null;
    var $child = null;
	var $privacy;
	var $page;
}
