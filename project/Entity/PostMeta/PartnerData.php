<?php
namespace Wizzaro\Partners\Entity\PostMeta;

use Wizzaro\Partners\Collections\PostTypes;

class PartnerData {
    
    /**
     * @var int
     */
    private $post_id;
    
    /**
     * @constructor
     */
    public function __construct( $post_id ) {
        $this->post_id = $post_id;
        $this->exchange_array( get_post_meta( $this->post_id, '_wizzaro_partners_partner_data', true ) );
    }
    
    /**
     * @param array $data
     */
    public function exchange_array( $data ) {
        if ( is_array( $data ) ) {
            foreach( $data as $item_key => $item_value ) {
                $this->$item_key = $item_value;
            }
        }
    }

    /**
     * @return array
     */
    public function get_array_copy() {
        return get_object_vars($this);
    }
    
    public function save() {
        if ( ! update_post_meta( $this->post_id, '_wizzaro_partners_partner_data',  $this->get_array_copy() ) ) {
            add_post_meta( $this->post_id, '_wizzaro_partners_partner_data',  $this->get_array_copy(), true);
        }
    }
}