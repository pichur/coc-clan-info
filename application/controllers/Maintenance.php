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
        $value = $this->clanloader->test();
        $this->load->model('Clan');
        $e = $this->Clan->getLastEntry();
        echo $e;
    }
    
    private function cyclic () {
        $this->load->library('ClanLoader');
        $this->clanloader->loadAll();
    }
    
    private function planned () {
        
    }
    
}
