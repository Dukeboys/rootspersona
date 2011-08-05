<?php
/**
 * Object represents connection to database
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class RP_Connection {

    /**
     *
     * @var resource 
     */
    private $connection;

    /**
     * @todo Description of function Connection
     * @param  $credentials
     * @return
     */
    public function __construct( $credentials ) {
        $this->connection = RP_Connection_Factory::get_connection( $credentials );
    }


    /**
     * @todo Description of function close
     * @param
     * @return
     */
    public function close() {
       RP_Connection_Factory::close( $this->connection );
    }
    /**
     * Wykonanie zapytania sql na biezacym polaczeniu
     *
     * @param sql zapytanie sql
     * @return wynik zapytania
     */
    public function execute_query( $sql ) {
        return mysql_query( $sql, $this->connection );
    }
}
?>
