<?php

class ClanAnalyzer {
    
    /**
     * @var ClanTotals
     */
    private $totals;
    
    /**
     * @var array[ClanHistory]
     */
    private $historyList;
    
    /**
     * @var ClanHistory
     */
    private $previousHistory;
    
    /**
     * @var ClanHistory
     */
    private $currentHistory;
    
    /**
     * @var ClanHistory
     */
    private $previousPlayers;
    
    public function analyze () {
        debug('Clan analyze');
        
        if (!$this->init()) {
            debug('Nothing to add');
            return;
        }
        
        $this->process();
        
        debug('Clan totals save');
        $this->totals->save();
        
        debug('Clan analyze finished');
    }
    
    private function init () {
        $this->totals = ClanTotals::getBy(['tag' => config_item('clan_tag')]);
        
        $this->historyList = ClanHistory::loadByOrder('timestamp', $this->totals->clanTimestamp, null, 'ASC');
        
        if (!$this->historyList) {
            return false;
        }
        
        if ($this->totals == null) {
            $this->currentHistory = array_shift($this->historyList);
            $this->totals = new ClanTotals();
            $this->totals->init($this->currentHistory);
            foreach ($this->currentHistory->getMemberList() as $currentPlayer) {
                $playerAnalyzer = new PlayerAnalyzer(null, $currentPlayer);
                $playerAnalyzer->analyze();
            }
        } else {
            $this->currentHistory = ClanHistory::getBy(['timestamp' => $totals->clanTimestamp]);
        }
        
        $this->setPrevious();
        
        return true;
    }
    
    private function process () {
        /**
         * @var ClanHistory $history
         */
        foreach ($this->historyList as $history) {
            $this->currentHistory = $history;
            debug('Add clan history ' . $this->currentHistory->timestamp->format('Y-m-d H:i:s'));
            $this->totals->addClanHistory($this->currentHistory);
            
            foreach ($this->currentHistory->getMemberList() as $currentPlayer) {
                $playerAnalyzer = new PlayerAnalyzer($this->previousPlayers[$currentPlayer->tag], $currentPlayer);
                $playerAnalyzer->analyze();
            }
            
            $this->setPrevious();
        }
    }
    
    private function setPrevious () {
        $this->previousHistory = $this->currentHistory;
        
        $this->previousPlayers = [];
        $memberList = $this->previousHistory->getMemberList();
        foreach ($memberList as $previousPlayer) {
            $this->previousPlayers[$previousPlayer->tag] = $previousPlayer;
        }
    }
    
}
