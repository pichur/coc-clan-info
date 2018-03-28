<?php

class ClanLoader {
    
    public function loadAll () {
        // Same timestamp for all calls
        $timestamp = time();
        
        /**
         * @var Clan $clan
         */
        $clan = $this->load($timestamp, 'clan');
        foreach ($clan->memberList as $member) {
            $this->load($timestamp, 'player', $member->tag);
        }
        //$this->load($timestamp, 'members'   );
        $this->load($timestamp, 'currentwar');
        
    }
    
    public function test () {
        $value = file_get_contents (APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . 'clan.json');
        $json = json_decode($value);
        $timestamp = 1234567890;
        $object = Clan::parseJson($timestamp, $json);
        return $object;
    }
    
    private function load ($timestamp, $mode = 'clan', $tag = null) {
        // Same timestamp for all calls
        $date = date('Y-m-d_H-i-s', $timestamp);
        
        $url = 'https://api.clashofclans.com/v1/';
        if ($mode == 'player') {
            $url .= 'players/' . urlencode($tag);
        } else {
            $url .= 'clans/' . urlencode(config_item('clan_tag'));
            if ($mode != 'clan') {
                $url .= '/' . $mode;
            }
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'authorization: Bearer ' . config_item('coc_api')
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $value = curl_exec($curl);
        curl_close($curl);
        
        $filename = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . $mode;
        if ($tag) $filename .= '_' . substr($tag, 1);
        $filename .= '_' . $date . '.json';
        file_put_contents($filename, $value);
        
        $result = json_decode($value);
        return $result;
    }
    
    private function clan () {
        
    }
    
    private function members () {
        
    }
    
    private function currentWar () {
        
    }
    
}
