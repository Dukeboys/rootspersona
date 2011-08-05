<?php
/**
 * ArrayList
 *
 * @author: Tomasz Jazwinski
 * @date: 2007-11-28
 */
class RP_Array_List {

    private $tab;
    private $size;

    /**
     * @todo Description of function ArrayList
     * @param
     * @return
     */
    public function RP_Array_List() {
        $this->tab = array();
        $this->size = 0;
    }
    /**
     * Dodanie wartosci do listy
     */
    public function add( $value ) {
        $this->tab[$this->size] = $value;
	$this->size = ( $this->size ) + 1;
    }
    /**
     * Pobranie elementu o numerze podanym
     * jako parametr metody
     */
    public function get( $idx ) {
        return $this->tab[$idx];
    }
    /**
     * Pobranie ostatniego elementu
     */
    public function get_last() {
        if ( $this->size == 0 ) {
            return null;
        }
        return $this->tab[( $this->size ) - 1];
    }
    /**
     * Rozmiar listy
     */
    public function size() {
        return $this->size;
    }
    /**
     * Czy lista jest pusta
     */
    public function is_empty() {
        return ( $this->size ) == 0;
    }
    /**
     * Usuniecie ostatniego
     */
    public function remove_last() {
        return $this->size = ( $this->size ) - 1;
    }
}
?>
