<?php

class Maintenance extends CI_Controller {
    
    /**
     * @var ClanLoader
     */
    public $clanloader;
    
    /**
     * @var Clan
     */
    public $Clan;
    
    public function call () {
        $this->cyclic ();
        $this->planned();
    }
    
    public function test () {
        $this->load->library('ClanLoader');
        $this->load->database();
        /*
         * @var $value Clan
         */
        $value = $this->clanloader->test();
        $value->save();
        echo $value->tag . ' end';
    }
    
    private function cyclic () {
        $this->load->library('ClanLoader');
        $this->clanloader->loadAll();
    }
    
    private function planned () {
        
    }
    
}
