<?php

class Analyze extends CI_Controller {
    
    use Transactional;
    
    public function clan () {
        $this->load->database();
        
        $this->trans_begin();
        
        ClanAnalyzer::analyze();
        /*
        $clanAnalyzer = ClanAnalyzer::construct($clanHistory);
        $clanAnalyzer->analyze();
        $previousClanHistory = ClanHistory::loadSingleByOrder('timestamp', $timestamp);
        $previousTimestamp = $previousClanHistory ? $previousClanHistory->timestamp : null;
        foreach ($clanHistory->memberList as $playerHistory) {
            $playerAnalyzer = PlayerAnalyzer::construct($playerHistory, $previousTimestamp);
            $playerAnalyzer->analyze();
        }
        */
        
        $this->trans_complete();
    }
    
    public function player () {
        $this->load->database();
        
        $this->trans_begin();
        
        
        
        $this->trans_complete();
    }
    
}
