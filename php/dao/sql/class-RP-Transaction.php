<?php
/**
 * Database transaction
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class RP_Transaction {

    private static $transactions;
    private $connection;

    /**
     * @todo Description of function Transaction
     * @param  $credentials
     * @param  $isQuery[optional]        default value : false
     * @return
     */
    public function __construct( $credentials, $is_query = false ) {
        $this->connection = new RP_Connection( $credentials );
        if ( ! RP_Transaction::$transactions ) {
           RP_Transaction::$transactions = new RP_Array_List();
        }
       RP_Transaction::$transactions->add( $this );
        if ( ! $is_query )$this->connection->execute_query( 'BEGIN' );
    }


    /**
     * @todo Description of function close
     * @param
     * @return
     */
    public function close() {
       $this->connection->close();
       RP_Transaction::$transactions->remove_last();
    }
    /**
     * Zakonczenie transakcji i zapisanie zmian
     */
    public function commit() {
       $this->connection->execute_query( 'COMMIT' );
       $this->connection->close();
       RP_Transaction::$transactions->remove_last();
    }

    /**
     * Zakonczenie transakcji i wycofanie zmian
     */
    public function rollback() {
        $this->connection->execute_query( 'ROLLBACK' );
        $this->connection->close();
       RP_Transaction::$transactions->remove_last();
    }
    /**
     * Pobranie polaczenia dla obencej transakcji
     *
     * @return polazenie do bazy
     */
    public function get_connection() {
        return $this->connection;
    }
    /**
     * Zwraca obecna transakcje
     *
     * @return transkacja
     */
    public static function get_current_transaction() {
        if ( RP_Transaction::$transactions ) {
            $tran = RP_Transaction::$transactions->get_last();
            return $tran;
        }
        return;
    }
}
?>
