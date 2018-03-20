<?php

class ClanLoader {
    
    const COC_API = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjdhMzljMzFlLTNlNzktNGYzNi05OWY5LTcwYWZjN2ZlZWFiMyIsImlhdCI6MTUyMTMxMjkzMCwic3ViIjoiZGV2ZWxvcGVyLzRjMjdhYjhiLWEyNmQtZDM3Yi1hNmNmLWFjYmVhOTZhNWZjNSIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjg1LjIyMS4yMDQuMTk4IiwiOTEuMjI3LjEyMy44MCIsIjc5LjE3My40NC42NSJdLCJ0eXBlIjoiY2xpZW50In1dfQ.rTtCn9GJ0DtvypYjj0ONZeubwgDv_Fg5oo3WgKqw5qbbjT7lHxLMH60NT1_AC2ppDk5juCuP39LFBBKNCsSziw';
    const CLAN_TAG = '#PVJC0RR8';
    
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
        curl_setopt($curl, CURLOPT_URL, 'https://api.clashofclans.com/v1/clans/' . urlencode(self::CLAN_TAG) . $suffix);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'authorization: Bearer ' . self::COC_API
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

class xLocation
{
    public $id; //int
    public $name; //String
    public $isCountry; //boolean
    public $countryCode; //String
}

class xBadgeUrls
{
    public $small; //String
    public $large; //String
    public $medium; //String
}

class xIconUrls
{
    public $small; //String
    public $tiny; //String
    public $medium; //String
}

class xLeague
{
    public $id; //int
    public $name; //String
    public $iconUrls; //IconUrls
}

class xMemberList
{
    public $tag; //String
    public $name; //String
    public $role; //String
    public $expLevel; //int
    public $league; //League
    public $trophies; //int
    public $versusTrophies; //int
    public $clanRank; //int
    public $previousClanRank; //int
    public $donations; //int
    public $donationsReceived; //int
}

class xClan
{
    public $tag; //String
    public $name; //String
    public $type; //String
    public $description; //String
    public $location; //Location
    public $badgeUrls; //BadgeUrls
    public $clanLevel; //int
    public $clanPoints; //int
    public $clanVersusPoints; //int
    public $requiredTrophies; //int
    public $warFrequency; //String
    public $warWinStreak; //int
    public $warWins; //int
    public $warTies; //int
    public $warLosses; //int
    public $isWarLogPublic; //boolean
    public $members; //int
    public $memberList; //array(MemberList)
}

