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
        $timestamp = PlayerTotals::db()->select_max('timestamp')->from(PlayerTotals::table())->get()->first_row();
        if ($timestamp->timestamp) {
            $timestamp = Model::dbToDate($res->timestamp);
        } else {
            $timestamp = null;
        }
        
        $historyList = PlayerHistory::loadByOrder('timestamp', $timestamp, null, 'ASC');
        $totalsMap = [];
        foreach ($historyList as $history) {
            $totals = $totalsMap[$history->tag];
            if (!$totals) {
                $totals = PlayerTotals::getBy(['tag' => $history->tag]);
                if (!$totals) {
                    $totals = new PlayerTotals($history);
                    $totals[$history->tag] = $totals;
                    continue;
                }
                $totals[$history->tag] = $totals;
            }
            $totals->
        }
        
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
