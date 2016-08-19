<?php
namespace Wizzaro\Partners\Entity;

use Wizzaro\Partners\Option\PartnerData;

class PostType extends AbstractPostType {
    
    private $_options = false;

    public function get_option_instance() {
        if ( ! $this->_options ) {
            $this->_options = new PartnerData( $this->get_setting( 'post_type' ) );
        }   
        
        return $this->_options;     
    }
}
