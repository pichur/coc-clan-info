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
            $this->load($timestamp, 'player', $member->tag, $member);
        }
        //$this->load($timestamp, 'members'   );
        $this->load($timestamp, 'currentwar');
        
    }
    
    public function read () {
        $directory = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR;
        $filesY = scandir($directory);
        foreach ($filesY as $fileY) {
            if ($fileY && ($fileY[0] != '.') && is_dir($directory . $fileY)) {
                $dirY = $directory . $fileY . DIRECTORY_SEPARATOR;
                $filesM = scandir($dirY);
                foreach ($filesM as $fileM) {
                    if ($fileM && ($fileM[0] != '.') && is_dir($dirY . $fileM)) {
                        $dirYM = $dirY . $fileM . DIRECTORY_SEPARATOR;
                        $filesD = scandir($dirYM);
                        foreach ($filesD as $fileD) {
                            if ($fileD && ($fileD[0] != '.') && is_dir($dirYM . $fileD)) {
                                $dirYMD = $dirYM . $fileD . DIRECTORY_SEPARATOR;
                                $filesH = scandir($dirYMD);
                                foreach ($filesH as $fileH) {
                                    if ($fileH && ($fileH[0] != '.') && is_dir($dirYMD . $fileH)) {
                                        $dir = $dirYMD . $fileH . DIRECTORY_SEPARATOR;
                                        $object = $this->test($dir);
                                        return $object;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    private function test ($dir) {
        $clanValue = file_get_contents ($dir . 'clan.json');
        $clanJson = json_decode($clanValue);
        $timestamp = time();
        $clanObject = Clan::parseJson($timestamp, $clanJson);
        return $clanObject;
    }
    
    private function load ($timestamp, $mode = 'clan', $tag = null, $object = null) {
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
        
        $date = date('Y_m_d_H-i-s', $timestamp);
        $date = str_replace('_', DIRECTORY_SEPARATOR, $date);
        $path = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'calls' . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filename = $path . $mode;
        if ($tag) $filename .= '_' . substr($tag, 1);
        $filename .= '.json';
        file_put_contents($filename, $value);
        
        $result = json_decode($value);
        
        if ($object) {
            foreach ($result as $k => $v) {
                $object->$k = $v;
            }
            return $object;
        } else {
            return $result;
        }
    }
    
    private function clan () {
        
    }
    
    private function members () {
        
    }
    
    private function currentWar () {
        
    }
    
}
