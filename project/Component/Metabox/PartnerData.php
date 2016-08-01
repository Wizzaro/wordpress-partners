<?php
namespace Wizzaro\Partners\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Partners\Config\PluginConfig;

use Wizzaro\Partners\Entity\PostMeta\PartnerData as PartnerDataEntity;
use Wizzaro\Partners\Collections\PostTypes;

class PartnerData extends AbstractMetabox {
    
    private $_config;
    
    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }
    
    protected function _get_metabox_config() {
        
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-partner-data',
                'title' => __( 'Partner Data', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'normal',
                'priority' => 'high'
            );
        }
        
        return $this->_config;
    }
    
    public function render( $post ) {
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
            'partner_data_attributes' => PostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'partner_data_attributes' ),
            'partner_data' => new PartnerDataEntity( $post->ID ) 
        ) );
    }
    
    public function save( $post_id, $post ) {

        if( 
            ! is_admin()
            || ! isset ( $_POST['wizzaro_partners_parner'] )  
            || ! isset ( $_POST['wizzaro_partners_partner_data_edit'] ) 
            || ! wp_verify_nonce( $_POST['wizzaro_partners_partner_data_edit'], 'wizzaro_partners_partner_data_edit_nounce' ) ) {
            return;
        }
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;   
        }

        $post_data = new PartnerDataEntity( $post->ID );
        $new_post_data = $_POST['wizzaro_partners_parner'];
        
        if ( is_array( $new_post_data ) ) {
            $filter_instance = Filter::get_instance();
            
            if( array_key_exists( 'street', $new_post_data ) ) {
                $post_data->street = $filter_instance->filter_text( $new_post_data['street'] );
            }
            
            //--------------------------------------------------------------------------------------------------------------    
            
            if( array_key_exists( 'zip_code', $new_post_data ) ) {
                $post_data->zip_code = $filter_instance->filter_text( $new_post_data['zip_code'] );
            }
            
            //--------------------------------------------------------------------------------------------------------------    
            
            if( array_key_exists( 'city', $new_post_data ) ) {
                $post_data->city = $filter_instance->filter_text( $new_post_data['city'] );
            }
            
            //--------------------------------------------------------------------------------------------------------------    
            
            if( array_key_exists( 'phone', $new_post_data ) ) {
                $post_data->phone = $filter_instance->filter_text( $new_post_data['phone'] );
            }
            
            //--------------------------------------------------------------------------------------------------------------    
            
            if( array_key_exists( 'email', $new_post_data ) ) {
                $post_data->email = sanitize_email( $new_post_data['email'] );
            }

            //--------------------------------------------------------------------------------------------------------------    
            
            if( array_key_exists( 'website_url', $new_post_data ) ) {
                $post_data->website_url = $filter_instance->filter_url( $new_post_data['website_url'] );
            }
        }
        
        $post_data->save();
    }
}
