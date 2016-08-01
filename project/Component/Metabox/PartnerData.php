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
    
    protected function __construct() {
        add_action( 'do_meta_boxes', array( $this, 'register' ) );
        add_action( 'save_post', array( $this, 'save' ), 11, 2 );
    }
    
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
        $post_type = PostTypes::get_instance()->get_post_type( $post->post_type );
        
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
            'post_type_option_instance' => $post_type->get_option_instance(),
            'supports' => $post_type->get_setting( 'supports' ),
            'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
            'partner_data' => new PartnerDataEntity( $post->ID ) 
        ) );
    }
    
    public function save( $post_id, $post ) {

        if( 
            ! is_admin()
            || wp_is_post_revision( $post_id )
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
            $partner_data_attributes = PostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'partner_data_attributes' );
            
            $filter_instance = Filter::get_instance();
            
            foreach ( $partner_data_attributes as $attribute_key => $attribute_settings ) {
                if( array_key_exists( $attribute_key, $new_post_data ) ) {
                    switch ( $attribute_settings['type'] ) {
                        case 'phone':
                            $post_data->$attribute_key = $filter_instance->filter_text( $new_post_data[$attribute_key] );
                            break;
                        case 'email':
                            $post_data->$attribute_key = sanitize_email( $new_post_data[$attribute_key] );
                            break;
                        case 'url':
                            $post_data->$attribute_key = $filter_instance->filter_url( $new_post_data[$attribute_key] );
                            break;
                        default:
                            $post_data->$attribute_key = $filter_instance->filter_text( $new_post_data[$attribute_key] );
                            break;
                    }
                }
            }
        }
        
        $post_data->save();
    }
}
