<?php
namespace Wizzaro\Partners\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Partners\Config\PluginConfig;

use Wizzaro\Partners\Entity\PostMeta\SliderElements as SliderElementsEntity;
use Wizzaro\Partners\Collections\SlidersPostTypes;

class SliderElements extends AbstractMetabox {
    
    private $_config;
    
    private $_input_name = 'wizzaro_partner_slider_elems';
    
    protected function __construct() {
        parent::__construct();
        add_action( 'admin_footer', array( $this, 'insert_js_templates' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_style' ) );
    }
    
    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }
    
    protected function _get_metabox_config() {
        
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-slider-elements',
                'title' => __( 'Elements', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'normal',
                //'priority' => 'normal'
            );
        }
        
        return $this->_config;
    }
    
    public function render( $post ) {
        $languages_domain = PluginConfig::get_instance()->get( 'languages', 'domain' );
        
        wp_register_script( 'wizzaro-partners-slider-metabox-elements-js', PluginConfig::get_instance()->get_js_admin_url() . 'metabox-elements.js', array( 'jquery', 'jquery-ui-selectable', 'backbone', 'underscore' ), '1.0.0' , true );

        $js_config = array(
            'sync_elements' => array(
                'action' => PluginConfig::get_instance()->get( 'ajax_actions', 'metabox_elements_sync' ),
                'nonce' => wp_create_nonce( 'wizzaro_partners_elements_sync_nonce' ),
                'elements_post_type' => SlidersPostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'elements_post_type' )
            ),
            'l10n' => array(
                'delete_elements' => __( 'Are you sure you want to delete these elements?', $languages_domain ),
                'delete_element' => __( 'Are you sure you want to delete this element?', $languages_domain )
            )
        );
        
        wp_localize_script( 'wizzaro-partners-slider-metabox-elements-js', 'wpWizzaroPartnersMetaboxElementsConfig', $js_config );
        wp_enqueue_script( 'wizzaro-partners-slider-metabox-elements-js' );
        
        $elements_entity = new SliderElementsEntity( $post->ID );
        
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'post' => $post,
            'languages_domain' => $languages_domain,
            'elemnts' => $elements_entity->getElements(),
            'input_name' => $this->_input_name
        ) );
    }

    public function insert_js_templates() {
        global $post;
        
        if ( $post->post_type && SlidersPostTypes::get_instance()->get_post_type( $post->post_type ) !== false ) {
            View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'js-templates', array(
                'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
                'input_name' => $this->_input_name
            ));
        }
    }
    
    public function action_enqueue_style() {
        global $post;
        
        if ( $post->post_type && SlidersPostTypes::get_instance()->get_post_type( $post->post_type ) !== false ) {
            wp_enqueue_style( 'wizzaro-partners-metabox-elements', PluginConfig::get_instance()->get_css_admin_url() . 'metabox-elements.css' );
        }
    }
    
    public function save( $post_id, $post ) {
        
        if( 
            ! is_admin()
            || wp_is_post_revision( $post_id )
            || ! isset ( $_POST['wizzaro_partners_elements_edit'] ) 
            || ! wp_verify_nonce( $_POST['wizzaro_partners_elements_edit'], 'wizzaro_partners_elements_edit_nounce' ) ) {
            return;
        }
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;   
        }
        
        $new_elements = new SliderElementsEntity( $post->ID );
        $new_elements->elements = array();
                
        if ( isset ( $_POST[$this->_input_name] ) && is_array( $_POST[$this->_input_name] ) ) {
            $elements_post_type = SlidersPostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'elements_post_type' );
            
            foreach( $_POST[$this->_input_name] as $elem_id ) {
                $elem = get_post( $elem_id );
                
                if ( $elem && ! strcasecmp( $elem->post_type, $elements_post_type ) && ! in_array( $elem->ID, $new_elements->elements ) ) {
                    array_push( $new_elements->elements, $elem->ID );
                }
            }
        }
        
        $new_elements->save();
    }
}