<?php

class Maintenance extends CI_Controller {
    
    use Transactional;
    
    public function read ($start, $stop) {
        $this->load->database();
        
        $this->trans_begin();
        
        Loader::read($start, $stop);
        
        debug('Read');
        
        $this->trans_complete();
    }
    
    public function clan ($year, $month, $day, $time) {
        $this->load->database();
        
        $this->trans_begin();
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $clan ClanHistory
         */
        $clan = $this->loader->clanFile($dir, $year, $month, $day, $time);
        $clan->save();
        
        $this->trans_complete();
    }
    
    public function war ($year, $month, $day, $time) {
        $this->load->database();
        
        $this->trans_begin();
        
        $ds = DIRECTORY_SEPARATOR;
        $dir = APPPATH.'logs'.$ds.'calls'.$ds.$year.$ds.$month.$ds.$day.$ds.$time.$ds;
        
        /*
         * @var $war War
         */
        $war = $this->loader->warFile($dir, $year, $month, $day, $time);
        $war->save();
        
        $this->trans_complete();
    }
    
    public function cyclic () {
        debug('cyclic');
        
        $this->load->database();
        
        $this->trans_begin();
        
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
        
        $this->trans_complete();
    }
    
    public function planned () {
        $this->load->database();
        
        $this->trans_begin();
        
        $timestamp = new DateTime();
        
        $war = War::loadLast();
        if (($war->state != 'warEnded') && ($war->endTime < $timestamp)) {
            $war = $this->loader->warCall($timestamp);
            $war->save();
        }
        
        $this->trans_complete();
    }
    
}
