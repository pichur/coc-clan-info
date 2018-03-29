<?php

class Location extends Model {
    
    /** @var integer */ public $id         ;
    /** @var string  */ public $name       ;
    /** @var boolean */ public $isCountry  ;
    /** @var string  */ public $countryCode;
    
    public function save () {
        $query = $this->db->get_where('Location', 'id = ' . $this->id);
        $result = $query->result();
        if (is_array($result)) {
            $count = count($result);
            if ($count == 0) {
                $this->db->insert('Location', $this);
                return $this->id;
            } else if ($count == 1) {
                return $this->id;
            } else {
                throw new Exception('Non unique key, ' . $count . 'results for id=' . $this->id);
            }
        } else {
            throw new Exception('Unknown result ' . $result);
        }
    }
    
}
