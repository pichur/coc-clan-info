<?php

class StaticModel extends Model {
    
    /** @var integer */ public $id;
    
    public function save () {
        $query = $this->db->get_where($this->table(), 'id = ' . $this->id);
        $result = $query->result();
        if (is_array($result)) {
            $count = count($result);
            if ($count == 0) {
                $this->db->insert($this->table(), $this);
                return $this->id;
            } else if ($count == 1) {
                return $this->id;
            } else {
                throw new Exception('Non unique key, ' . $count . ' results for id=' . $this->id . ' in table ' . $this->table());
            }
        } else {
            throw new Exception('Unknown result ' . $result);
        }
    }
    
}