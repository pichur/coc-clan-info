<?php

class ClanLoader {
    
    public function loadAll () {
        $this->load('clan');
        $this->load('members');
        $this->load('currentwar');
    }
    
    public function test () {
        $value = file_get_contents (APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . 'clan.json');
        $json = json_decode($value);
        $object = Clan::parseJson($json);
        return $object;
    }
    
    private function load ($mode = 'clan') {
        $suffix = '';
        if ($mode != 'clan') {
            $suffix = '/' . $mode;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, 'https://api.clashofclans.com/v1/clans/' . urlencode($config['clan_tag']) . $suffix);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'authorization: Bearer ' . $config['coc_api']
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $api = curl_exec($curl);
        curl_close($curl);
        file_put_contents(APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . 'call_' . date('Y-m-d_H-i-s') . '_' . $mode . '.json', $api);
        $value = json_decode($api);
        return $value;
    }
    
    private function clan () {
        
    }
    
    private function members () {
        
    }
    
    private function currentWar () {
        
    }
    
}
