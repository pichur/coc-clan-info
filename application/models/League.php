<?php

class League extends StaticModel {
    
    public static $fieldMapping = [
        'id'       => ['key' => true],
        'iconUrls' => ['type' => 'OneToOne', 'target' => IconUrls::class],
    ];
    
    /** @var integer  */ public $id      ;
    /** @var string   */ public $name    ;
    /** @var IconUrls */ public $iconUrls;
    
    public function save () {
        parent::save();
        
        $this->iconUrls->id = $this->id;
        $this->iconUrls->save();
    }
    
}
