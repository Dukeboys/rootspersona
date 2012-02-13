<?php


/**
 * @todo Description of class RP_Credentials
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
class RP_Credentials {
    var $hostname = DB_HOST;
    var $dbuser = DB_USER;
    var $dbpassword = DB_PASSWORD;
    var $dbname = DB_NAME;
    var $prefix = null;


    /**
     * @todo Description of function getHostname
     * @param
     * @return
     */
    public function get_hostname() {
        return $this->hostname;
    }


    /**
     * @todo Description of function getUser
     * @param
     * @return
     */
    public function get_user() {
        return $this->dbuser;
    }


    /**
     * @todo Description of function getPassword
     * @param
     * @return
     */
    public function get_password() {
        return $this->dbpassword;
    }


    /**
     * @todo Description of function getDatabaseName
     * @param
     * @return
     */
    public function get_database_name() {
        return $this->dbname;
    }


    /**
     * @todo Description of function getPrefix
     * @param
     * @return
     */
    public function get_prefix() {
        return $this->prefix;
    }


    /**
     * @todo Description of function setHostname
     * @param  $hostname
     * @return
     */
    public function set_hostname( $hostname ) {
        $this->hostname = $hostname;
    }


    /**
     * @todo Description of function setUser
     * @param  $user
     * @return
     */
    public function set_user( $user ) {
        $this->dbuser = $user;
    }


    /**
     * @todo Description of function setPassword
     * @param  $password
     * @return
     */
    public function set_password( $password ) {
        $this->dbpassword = $password;
    }


    /**
     * @todo Description of function setDatabaseName
     * @param  $dbname
     * @return
     */
    public function set_database_name( $dbname ) {
        $this->dbname = $dbname;
    }


    /**
     * @todo Description of function setPrefix
     * @param  $prefix
     * @return
     */
    public function set_prefix( $prefix ) {
        $this->prefix = $prefix;
    }
}
