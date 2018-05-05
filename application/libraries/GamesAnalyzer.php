<?php

class GamesAnalyzer {
    
    /**
     * @var ClanTotals
     */
    private $totals;
    
    /**
     * @var array[Games]
     */
    private $gamesList;
    
    public function __construct () {
        $this->totals = ClanTotals::getBy(['tag' => config_item('clan_tag')]);
        
        $this->gamesList = Games::loadByOrder('endTime', $this->totals->gamesTimestamp, null, 'ASC');
    }
    
    public function analyze () {
        debug('Games analyze');
        
        if (!$this->totals) {
            throw new Exception('Games analyze need analyzed clan');
        }
        
        if (!$this->gamesList) {
            debug('Nothing to add');
            return;
        }
        
        $this->process();
        
        debug('Clan totals save');
        $this->totals->save();
        
        debug('Games analyze finished');
    }
    
    private function process () {
        foreach ($this->gamesList as $games) {
            if ($games->finished) {
                debug('Add games history ' . $games->number . ', end date ' . $games->endTime->format('Y-m-d H:i:s'));;
                $this->totals->addGamesHistory($games);
                
                foreach ($games->getPlayers() as $warPlayer) {
                    $playerTotals = PlayerTotals::getBy(['tag' => $warPlayer->tag]);
                    $playerTotals->addWar($war, $warPlayer);
                    $playerTotals->save();
                }
            }
        }
    }
    
}
