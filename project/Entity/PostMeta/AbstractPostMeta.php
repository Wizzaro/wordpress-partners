<?php
namespace Wizzaro\Partners\Entity\PostMeta;

abstract class AbstractPostMeta {
    
    /**
     * @var int
     */
    private $post_id;
    
    /**
     * @var string
     */
    protected $_meta_data_key = '';
    
    /**
     * @constructor
     */
    public function __construct( $post_id ) {
        $this->post_id = $post_id;
        $this->exchange_array( get_post_meta( $this->post_id, $this->_meta_data_key, true ) );
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
        if ( ! update_post_meta( $this->post_id, $this->_meta_data_key,  $this->get_array_copy() ) ) {
            add_post_meta( $this->post_id, $this->_meta_data_key,  $this->get_array_copy(), true);
        }
    }
}