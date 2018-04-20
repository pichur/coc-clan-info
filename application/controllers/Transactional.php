<?php

trait Transactional {
    
    protected function trans_begin () {
        get_instance()->db->trans_begin();
    }
    
    protected function trans_complete () {
        get_instance()->db->trans_complete();
        
        if (get_instance()->db->trans_status() === FALSE) {
            debug('DB fail');
        }
    }
    
}
