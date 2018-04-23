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
        
        // Max timestamp of analyzed player
        $res = PlayerTotals::db()->select_max('timestamp')->from(PlayerTotals::table())->get();
        if ($res) {
            $key = ['timestamp >' => Model::dbToDate($res[0][0])];
        }
        $tags = PlayerHistory::db()->select('tag')->from(PlayerHistory::table())->where($key)->distinct()->get();
        foreach ($tags as $tag) {
            
        }
        
        $this->trans_complete();
    }
    
}
