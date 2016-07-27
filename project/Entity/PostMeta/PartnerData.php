<?php
namespace Wizzaro\Partners\Entity\PostMeta;

class PartnerData {
    
    /**
     * @var int
     */
    private $post_id;
    
    /**
     * @var string
     */
    public $street = null;
    /**
     * @var string
     */
    public $zip_code = null;
    /**
     * @var string
     */
    public $city = null;
    /**
     * @var string
     */
    public $phone = null;
    /**
     * @var string
     */
    public $email = null;
    /**
     * @var string
     */
    public $website_url = null;
    
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
        if ( is_array( $data) ) {
            $this->street = ( isset ( $data['street'] ) ) ? $data['street'] : null;
            $this->zip_code = ( isset ( $data['zip_code'] ) ) ? $data['zip_code'] : null;
            $this->city = ( isset ( $data['city'] ) ) ? $data['city'] : null;
            $this->phone = ( isset ( $data['phone'] ) ) ? $data['phone'] : null;
            $this->email = ( isset ( $data['email'] ) ) ? $data['email'] : null;
            $this->website_url = ( isset ( $data['website_url'] ) ) ? $data['website_url'] : null;
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