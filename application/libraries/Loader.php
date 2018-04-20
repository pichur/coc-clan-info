<?php

class Loader {
    
    /**
     * Load clan info from server
     * @return ClanHistory
     */
    public function clanCall ($timestamp = null) {
        if (!$timestamp) $timestamp = new DateTime();
        
        $clanJson = $this->load($timestamp, 'clan');
        foreach ($clanJson->memberList as $member) {
            debug('Member ' . $member->tag . ' call');
            $this->load($timestamp, 'player', $member->tag, $member);
        }
        
        debug('Parse clan');
        $clanObject = ClanHistory::parseJson($timestamp, $clanJson);
        
        return $clanObject;
    }
    
    /**
     * Load war info from server
     * @return ClanHistory
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
    
    public function read ($start, $stop) {
        if ($start) $start = DateTime::createFromFormat('Y-m-d\TH:i:s', $start);
        if ($stop ) $stop  = DateTime::createFromFormat('Y-m-d\TH:i:s', $stop );
        
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
                                        
                                        $timestamp = logFolderDateTime($fileY, $fileM, $fileD, $fileT);
                                        
                                        if ($start && ($timestamp < $start)) {
                                            continue;
                                        }
                                        if ($stop && ($timestamp > $stop)) {
                                            break 4;
                                        }
                                        $clanHistory = $this->clanFile($dir, $timestamp);
                                        $clanHistory->save();
                                        $clanAnalyzer = new ClanAnalyzer($clanHistory);
                                        $clanAnalyzer->analyze();
                                        $previousClanHistory = ClanHistory::loadSingleByOrder('timestamp', $timestamp);
                                        $previousTimestamp = $previousClanHistory ? $previousClanHistory->timestamp : null;
                                        foreach ($clanHistory->memberList as $playerHistory) {
                                            $playerAnalyzer = new PlayerAnalyzer($playerHistory, $previousTimestamp);
                                            $playerAnalyzer->analyze();
                                        }
                                        
                                        $war = $this->warFile($dir, $timestamp);
                                        $war->save();
                                        
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
    
    public function clanFile ($dir, DateTime $timestamp) {
        $clanValue = file_get_contents ($dir . 'clan.json');
        $clanJson = json_decode($clanValue);
        
        foreach ($clanJson->memberList as $player) {
            $playerValue = file_get_contents($dir . 'player_' . substr($player->tag, 1) . '.json');
            $playerJson = json_decode($playerValue);
            
            foreach ($playerJson as $k => $v) {
                $player->$k = $v;
            }
        }
        
        $clanObject = ClanHistory::parseJson($timestamp, $clanJson);
        
        return $clanObject;
    }
    
    public function warFile ($dir, DateTime $timestamp) {
        $warValue = file_get_contents ($dir . 'currentwar.json');
        $warJson = json_decode($warValue);
        
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
