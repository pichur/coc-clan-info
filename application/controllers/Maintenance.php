<?php

class Maintenance extends CI_Controller {
    
    /**
     * @var Loader
     */
    public $loader;
    
    public function read ($start, $stop) {
        $this->load->database();
        $this->load->library('Loader');
        $this->load->library('ClanAnalyzer');
        $this->load->library('WarAnalyzer');
        $this->load->library('PlayerAnalyzer');
        
        $this->loader->read($start, $stop);
        
        echo 'Read';
    }
    
    public function clan ($year, $month, $day, $time) {
        $this->load->database();
        $this->load->library('Loader');
        $this->load->library('ClanAnalyzer');
        $this->load->library('PlayerAnalyzer');
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $clan ClanHistory
         */
        $clan = $this->loader->clanFile($dir, $year, $month, $day, $time);
        $clan::db()->trans_start();
        $clan->save();
        $clan::db()->trans_complete();
        
        if ($clan::db()->trans_status() === FALSE) {
            echo 'DB fail';
        } else {
            echo $clan->tag . ' end';
        }
    }
    
    public function war ($year, $month, $day, $time) {
        $this->load->database();
        $this->load->library('Loader');
        $this->load->library('ClanAnalyzer');
        $this->load->library('WarAnalyzer');
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $war War
         */
        $war = $this->loader->warFile($dir, $year, $month, $day, $time);
        $war::db()->trans_start();
        $war->save();
        $war::db()->trans_complete();
        
        if ($war::db()->trans_status() === FALSE) {
            echo 'DB fail';
        } else {
            echo $war->tag . ' end';
        }
    }
    
    public function cyclic () {
        debug('cyclic');
        
        $this->load->database();
        $this->load->library('Loader');
        $this->load->library('ClanAnalyzer');
        $this->load->library('WarAnalyzer');
        $this->load->library('PlayerAnalyzer');
        
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
        $this->load->library('Loader');
        $this->load->library('ClanAnalyzer');
        $this->load->library('WarAnalyzer');
        
        $timestamp = new DateTime();
        
        $war = War::loadLast();
        if (($war->state != 'warEnded') && ($war->endTime < $timestamp)) {
            $war = $this->loader->warCall($timestamp);
            $war->save();
        }
    }
    
}
