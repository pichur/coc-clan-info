<?php

class Analyze extends CI_Controller {
    
    use Transactional;
    
    public function clear () {
        $this->load->database();
        
        $this->trans_begin();
        
        ClanTotals       ::delete();
        PlayerTotals     ::delete();
        PlayerClanPeriod ::delete();
        PlayerWarPeriod  ::delete();
        PlayerGamesPeriod::delete();
        
        $this->trans_complete();
        debug("Analyze cleared");
    }
    
    public function clan () {
        $this->load->database();
        
        $this->trans_begin();
        
        $clanAnalyzer = new ClanAnalyzer();
        $clanAnalyzer->analyze();
        
        $this->trans_complete();
    }
    
    public function war () {
        $this->load->database();
        
        $this->trans_begin();
        
        $warAnalyzer = new WarAnalyzer();
        $warAnalyzer->analyze();
        
        $this->trans_complete();
    }
    
    public function games () {
        $this->load->database();
        
        $this->trans_begin();
        
        $gamesAnalyzer = new GamesAnalyzer();
        $gamesAnalyzer->analyze();
        
        $this->trans_complete();
    }
    
}
