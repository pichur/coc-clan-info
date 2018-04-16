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
    
    /**
     * @var War
     */
    public $War;
    
    public function read () {
        $this->load->library('Loader');
        $this->load->database();
        $this->loader->read();
        echo 'Read';
    }
    
    public function clan ($year, $month, $day, $time) {
        $this->load->library('Loader');
        $this->load->database();
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $clan Clan
         */
        $clan = $this->loader->clanFile($dir, $year, $month, $day, $time);
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
        $war = $this->loader->warFile($dir, $year, $month, $day, $time);
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
        debug('cyclic');
        
        $this->load->library('Loader');
        $this->load->database();
        
        $timestamp = new DateTime();
        
        debug('clan call');
        $clan = $this->loader->clanCall($timestamp);
        debug('clan save');
        $clan->save();
        
        debug('war call');
        $war = $this->loader->warCall($timestamp);
        debug('war save');
        $war->save();
        
        debug('cyclic end');
    }
    
    public function planned () {
        $this->load->database();
        
        $timestamp = new DateTime();
        
        $war = $this->War->loadLast();
        if (($war->state != 'warEnded') && ($war->endTime < $timestamp)) {
            $war = $this->loader->warCall($timestamp);
            $war->save();
        }
    }
    
}
