<?php
/**
 * Created by PhpStorm.
 * User: paolo
 * Date: 13/05/16
 * Time: 16:52
 */


//required in function.php
class OrmModel {

    private $table_name = '';


    /**
     * on new class OrmModel set table name without prefix: default posts
     * OrmModel constructor.
     * @param string $table_name whitout prefix
     */
    public function __construct($table_name = 'posts'){
        $this->table_name = $table_name;
    }

    /**
     * get the name of the current table used
     * @return string name with out prefix
     */
    public function getTable(){
        return $this->table_name;
    }


    /**
     * triger table access
     * @return string the table access
     */
    public function table() {
        global $wpdb;

        return $wpdb->prefix.$this->table_name;
    }

    /**
     * get all intel from a table
     * @param string $conditions array([row_name] => condition)
     * @return object or false if the table is empty
     */

    public function all($conditions = array()) {

        $conditionsSql = '';
        if(!empty($conditions)){
            $conditionsSql = isset($conditions) ? (' WHERE ' . join(' AND ',$conditions)) : null;
        }

        if(!empty($this->table())) {
            global $wpdb;

            $sql = "SELECT * FROM ".$this->table() . $conditionsSql;

            return $wpdb->get_results($sql);
        }

        return false;
    }

    /**
     * to get only one resultat
     * @param array $conditions array([row_name] => condition )
     * @return object or false
     */

    public function row($conditions = array()) {

        $conditionsSql = '';
        if(!empty($conditions)){
            $conditionsSql = isset($conditions) ? (' WHERE ' . join(' AND ',$conditions)) : null;
        }

        if(!empty($this->table())) {
            global $wpdb;

            $sql = "SELECT * FROM ".$this->table() . $conditionsSql;

            return $wpdb->get_row($sql);
        }

        return false;
    }

    /**
     * GET ELEMENT BY IS ID
     * @param $id
     * @return an object as a row
     */

    public function findById($id) {
        global $wpdb;

        $sql = "SELECT * FROM ".$this->table() . " WHERE id = '".$id."'";

        return $wpdb->get_row($sql);
    }


    /**
     * To global seach of result
     * @param $type
     * @param $conditions
     * @return bool|object
     */


    public  function find($type = 'all', $conditions) {

        if($type == 'all') {
            return $this->all($conditions);
        } else if($type == 'row') {
            return $this->row($conditions);
        }

        return false;
    }

    /**
     * Insert data into table
     * @param $data array( column => value)
     * @return object
     */

    public  function create($data) {
        global $wpdb;

        if($wpdb->insert($this->table(),$data)) {
            $id = $wpdb->insert_id;

            $data = $this->findById($id);

            return $data;
        }

        return false;
    }

    /**
     * last insert Id call after create
     * @return mixed
     */

    public function lastId(){
        global $wpdb;

        return $wpdb->insert_id;
    }

    /**
     * update data
     * @param $data array( column => value)
     * @param $where  array( column => value)
     * @param null $format array( format)
     * @return mixed
     */

    public  function update($data, $where, $format = null) {
        global $wpdb;

        return $wpdb->update( $this->table(), $data, $where, $format );
    }

    /**
     * delet data
     * @param $data array( column => value)
     * @return mixed
     */

    public  function delete($data) {
        global $wpdb;

        return $wpdb->delete( $this->table(), $data );
    }
}