<?php
/**
 * Object represents sql query with params
 *
 * @author: http://phpdao.com
 * @date: 27.10.2007
 */
class RP_Sql_Query {
    var $txt;
    var $params = array();
    var $idx = 0;
    /**
     * Constructor
     *
     * @param String $txt zapytanie sql
     */
    function __construct( $txt, $prefix = null ) {
        if ( $prefix != null ) {
            $txt = str_replace( 'FROM rp_',  'FROM ' . $prefix . 'rp_', $txt );
            $txt = str_replace( 'INTO rp_',  'INTO ' . $prefix . 'rp_', $txt );
            $txt = str_replace( 'UPDATE rp_', 'UPDATE ' . $prefix . 'rp_', $txt );
            $txt = str_replace( 'JOIN rp_',  'JOIN ' . $prefix . 'rp_', $txt );
        }
        $this->txt = $txt;
    }
    /**
     * Set string param
     *
     * @param String $value value set
     */
    public function set_string( $value ) {
        $value = mysql_escape_string( $value );
        $this->params[$this->idx++] = "'" . $value . "'";
    }
    /**
     * Set string param
     *
     * @param String $value value to set
     */
    public function set( $value ) {
        $value = mysql_escape_string( $value );
        $this->params[$this->idx++] = "'" . $value . "'";
    }
    /**
     * Metoda zamienia znaki zapytania
     * na wartosci przekazane jako parametr metody
     *
     * @param String $value wartosc do wstawienia
     */
    public function set_number( $value ) {
        if ( $value === null ) {
            $this->params[$this->idx++] = 'null';
            return;
        }
        if ( ! Is_numeric( $value ) ) {
            throw new Exception( $value . ' is not a number' );
        }
        $this->params[$this->idx++] = "'" . $value . "'";
    }
    /**
     * Get sql query
     *
     * @return String
     */
    public function get_query() {
        if ( $this->idx == 0 ) {
            return $this->txt;
        }
        $p = explode( '?', $this->txt );
        $sql = '';
        for ( $i = 0; $i <= $this->idx; $i++ ) {
            if ( $i >= count( $this->params ) ) {
                $sql .= $p[$i];
            } else {
                $sql .= $p[$i] . $this->params[$i];
            }
        }
        return $sql;
    }
    /**
     * Function replace first char
     *
     * @param String $str
     * @param String $old
     * @param String $new
     * @return String
     */
    private function replace_first( $str, $old, $new ) {
        $len = strlen( $str );
        for ( $i = 0;
    $i < $len;
    $i++ ) {
            if ( $str[$i] == $old ) {
                $str = substr( $str, 0, $i ) . $new . substr( $str, $i + 1 );
                return $str;
            }
        }
        return $str;
    }
}
?>
