<?php

class Maintenance extends CI_Controller {
    
    /**
     * @var Loader
     */
    public $loader;
    
    /**
     * @var Clan
     */
    public $Clan;
    
    public function call () {
        $this->cyclic ();
        $this->planned();
    }
    
    public function read () {
        $this->load->library('Loader');
        /*
         * @var $value Clan
         */
        $value = $this->loader->read();
        echo $value->tag . ' end';
    }
    
    public function clan ($year, $month, $day, $time) {
        $this->load->library('Loader');
        $this->load->database();
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $clan Clan
         */
        $clan = $this->loader->clan($dir, $year, $month, $day, $time);
        $clan->db()->trans_start();
        $clan->save();
        $clan->db()->trans_complete();
        
        if ($clan->db()->trans_status() === FALSE) {
            echo 'DB fail';
        } else {
            echo $clan->tag . ' end';
        }
    }
    
    public function war ($year, $month, $day, $time) {
        $this->load->library('Loader');
        $this->load->database();
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $war War
         */
        $war = $this->loader->war($dir, $year, $month, $day, $time);
        $war->db()->trans_start();
        $war->save();
        $war->db()->trans_complete();
        
        if ($war->db()->trans_status() === FALSE) {
            echo 'DB fail';
        } else {
            echo $war->tag . ' end';
        }
    }
    
    public function cyclic () {
        $this->load->library('Loader');
        $this->load->database();
        
        $timestamp = time();
        
        $clan = $this->loader->clanX($timestamp);
        $clan->save();
        
        $war = $this->loader->warX($timestamp);
        $war->save();
    }
    
    private function planned () {
        
    }
    
}
