<?php
class Cron_Model extends CI_Model
{
    
    function __construct() 
    {
        parent::__construct();
        
    }
    /**
     * @desc insert data into specified table
     * @param string $tablename
     * @param array $valueArray an associative array of column name and their values
     * @return true on success or false otherwise
     */
    public function insertData($tablename='',$valueArray=array()) {
        return $this->db->insert_batch($tablename,$valueArray);
    }
    /**
     * update data into specified table
     * @param string $tablename
     * @param array $conditions associative array of column name and their values
     * @param array associative array of column name and their values
     * @return true on success or false otherwise
     */
     public function updateData($tablename='',$conditions=array(),$updateValueArray=array()) {
        foreach ($conditions as $key=>$val)
        {
             $this->db->where($key,$val);
        }
        return $this->db->update($tablename,$updateValueArray);
    }
    /**
     * Delete data into specified table
     * @param string $tablename
     * @param array $conditions associative array of column name and their values
     * @return true on success or false otherwise
     */
     public function deleteData($tablename='',$conditions=array()) {
        foreach ($conditions as $key=>$val)
        {
             $this->db->where($key,$val);
        }
        return $this->db->delete($tablename);
    }
     /**
     * Delete all data into specified table
     * @param string $tablename
     * @return true on success or false otherwise
     */
     public function emptyTable($tablename='') {
        //return $this->db->truncate($tablename);
        return $this->db->empty_table($tablename);
    }
    
    public function truncateTableByName($table='') {
        return $this->db->truncate($table);
    }
    
    /**
     * @desc check for existence of a record base on a condition
     * @param string $tablename
     * @param array $conditions associative array of column name and their values
     * @return resultset on success or empty otherwise
     */
    public function checkexistingData($tablename='',$conditions=array()) {
        $this->db->from($tablename);
        foreach ($conditions as $key=>$val)
        {
            $this->db->where($key,$val);
        }
        return $this->db->get()->result();
    }
    
     
    
}