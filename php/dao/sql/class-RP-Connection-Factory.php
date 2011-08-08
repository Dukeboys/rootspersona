<?php
/*
 * class RP_return connection to database
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
*/


/**
 * @todo Description of class RP_ConnectionFactory
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
class RP_Connection_Factory {
	/**
	 * Zwrocenie polaczenia
	 *
	 * @return polaczenie
	 */
	static public function get_connection( $credentials ) {
        $new_link = true;
		$conn = mysql_connect( $credentials->hostname, $credentials->dbuser, $credentials->dbpassword, $new_link );
		mysql_select_db( $credentials->dbname );
		mysql_set_charset( 'utf8', $conn );
		if ( ! $conn ) {
			throw new Exception( 'could not connect to database' );
		}
		return $conn;
	}
	/**
	 * Zamkniecie polaczenia
	 *
	 * @param connection polaczenie do bazy
	 */
	static public function close( $connection ) {
		mysql_close( $connection );
	}
}
?>
