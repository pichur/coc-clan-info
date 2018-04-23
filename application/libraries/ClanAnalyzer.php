<?php

class ClanAnalyzer {
    
    public static function analyze () {
        debug('Clan analyze');
        $totals = ClanTotals::getBy(['tag' => config_item('clan_tag')]);
        
        $historyList = ClanHistory::loadByOrder('timestamp', $totals->clanTimestamp, null, 'ASC');
        
        if (!$historyList) {
            debug('Nothing to add');
            return;
        }
        
        if ($totals == null) {
            $history = array_shift($historyList);
            $totals = new ClanTotals($history);
        }
        
        /**
         * @var ClanHistory $history
         */
        foreach ($historyList as $history) {
            debug('Add clan history ' . $history->timestamp->format('Y-m-d H:i:s'));
            $totals->addClanHistory($history);
        }
        
        debug('Clan totals save');
        $totals->save();
        
        debug('Clan analyze finished');
    }
    
}
