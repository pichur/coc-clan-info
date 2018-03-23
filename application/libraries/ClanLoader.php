<?php

class ClanLoader {
    
    public function loadAll () {
        // Same timestamp for all calls
        $timestamp = time();
        $this->load($timestamp, 'clan'      );
        $this->load($timestamp, 'members'   );
        $this->load($timestamp, 'currentwar');
    }
    
    public function test () {
        $value = file_get_contents (APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . 'clan.json');
        $json = json_decode($value);
        $timestamp = 1234567890;
        $object = Clan::parseJson($timestamp, $json);
        return $object;
    }
    
    private function load ($timestamp, $mode = 'clan') {
        // Same timestamp for all calls
        $date = date('Y-m-d_H-i-s', $timestamp);
        
        $suffix = '';
        if ($mode != 'clan') {
            $suffix = '/' . $mode;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, 'https://api.clashofclans.com/v1/clans/' . urlencode(config_item('clan_tag')) . $suffix);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'authorization: Bearer ' . config_item('coc_api')
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $value = curl_exec($curl);
        curl_close($curl);
        file_put_contents(APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . $mode . '_' . $date . '.json', $value);
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
