<?php


/**
 * @todo Description of class RP_RpPersona
 * @author ed4becky
 * @copyright 2010-2011  Ed Thompson  (email : ed@ed4becky.org)
 * @version 2.0.x
 * @package rootspersona_php
 * @subpackage
 * @category rootsPersona
 * @link www.ed4becky.net
 * @since 2.0.0
 * @license http://www.opensource.org/licenses/GPL-2.0
 */
class RP_Persona extends Rp_Simple_Person {
    var $facts;
    var $sources;
    var $ancestors;
    var $siblings;
    var $marriages;
    var $is_living;
    var $pscore;
    var $picFiles = array();
    var $picCaps = array();

    /**
     * @todo Description of function __construct
     * @param
     * @return
     */
    function __construct() {
        $this->privacy = RP_Persona_Helper::DEF;
    }
}
