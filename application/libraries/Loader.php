<?php

class Loader {
    
    public function loadAll () {
        // Same timestamp for all calls
        $timestamp = new DateTime();
        
        /**
         * @var Clan $clan
         */
        $clan = $this->load($timestamp, 'clan');
        foreach ($clan->memberList as $member) {
            $this->load($timestamp, 'player', $member->tag, $member);
        }
        
        /**
         * @var War $war
         */
        $war = $this->load($timestamp, 'currentwar');
        
    }
    
    /**
     * Load clan info from server
     * @return Clan
     */
    public function clanCall ($timestamp = null) {
        if (!$timestamp) $timestamp = new DateTime();
        
        $clanJson = $this->load($timestamp, 'clan');
        foreach ($clanJson->memberList as $member) {
            $this->load($timestamp, 'player', $member->tag, $member);
        }
        
        $clanObject = Clan::parseJson($timestamp, $clanJson);
        
        return $clanObject;
    }
    
    /**
     * Load war info from server
     * @return Clan
     */
    public function warCall ($timestamp = null) {
        if (!$timestamp) $timestamp = new DateTime();
        
        /**
         * @var War $war
         */
        $warJson = $this->load($timestamp, 'currentwar');
        
        $warObject = War::parseJson($timestamp, $warJson);
        
        return $warObject;
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
                                $filesT = scandir($dirYMD);
                                foreach ($filesT as $fileT) {
                                    if ($fileT && ($fileT[0] != '.') && is_dir($dirYMD . $fileT)) {
                                        $dir = $dirYMD . $fileT . DIRECTORY_SEPARATOR;
                                        $this->clanFile($dir, $fileY, $fileM, $fileD, $fileT)->save();
                                        $this->warFile ($dir, $fileY, $fileM, $fileD, $fileT)->save();
                                        echo $dir . PHP_EOL;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function clanFile ($dir, $year, $month, $day, $time) {
        $clanValue = file_get_contents ($dir . 'clan.json');
        $clanJson = json_decode($clanValue);
        
        $times = explode('-', $time);
        $timestamp = new DateTime();
        $timestamp->setDate($year, $month, $day);
        $timestamp->setTime($times[0], $times[1], $times[2]);
        
        foreach ($clanJson->memberList as $player) {
            $playerValue = file_get_contents($dir . 'player_' . substr($player->tag, 1) . '.json');
            $playerJson = json_decode($playerValue);
            
            foreach ($playerJson as $k => $v) {
                $player->$k = $v;
            }
        }
        
        $clanObject = Clan::parseJson($timestamp, $clanJson);
        
        return $clanObject;
    }
    
    public function warFile ($dir, $year, $month, $day, $time) {
        $warValue = file_get_contents ($dir . 'currentwar.json');
        $warJson = json_decode($warValue);
        
        $times = explode('-', $time);
        $timestamp = new DateTime();
        $timestamp->setDate($year, $month, $day);
        $timestamp->setTime($times[0], $times[1], $times[2]);
        
        $warObject = War::parseJson($timestamp, $warJson);
        
        return $warObject;
    }
    
    private function load (DateTime $timestamp, $mode = 'clan', $tag = null, $object = null) {
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
        
        $date = $timestamp->format('Y_m_d_H-i-s');
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
    
}
