<?php

class Analyze extends CI_Controller {
    
    use Transactional;
    
    public function clan () {
        $this->load->database();
        
        $this->trans_begin();
        
        ClanAnalyzer::analyze();
        
        $this->trans_complete();
    }
    
    public function clear () {
        $this->load->database();
        
        $this->trans_begin();
        
        ClanTotals  ::delete();
        PlayerTotals::delete();
        PlayerPeriod::delete();
        
        $this->trans_complete();
    }
    
}
