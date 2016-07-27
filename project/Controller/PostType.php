<?php
namespace Wizzaro\Partners\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;
use Wizzaro\Partners\Component\Metabox\PartnerData;

class PostType extends AbstractPluginController {
    
    public function init() {
        add_action( 'init', array( $this, 'action_register_post_types' ), 0 );
    }
    
    public function init_admin() {
        PartnerData::create();
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for all
    
    public function action_register_post_types() {
        $languages_domain = $this->_config->get( 'languages', 'domain' );
        
        $default_public_settings = array (
            'public'              => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );
        
        $default_private_settings = array(
            'public'              => false,
            'has_archive'         => false,
            'hierarchical'        => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'menu_position'       => 100,
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'rewrite'             => false,
            'query_var'           => false,
        );
        
        $supports = array( 'title', 'revisions' );
        
        if ( $this->_config->get( 'partner_data_attributes', 'logo' ) === true ) {
            array_push( $supports, 'thumbnail' );
        }
        
        if ( $this->_config->get( 'partner_data_attributes', 'description' ) === true ) {
            array_push( $supports, 'editor' );
        }
        
        $default_public_settings['supports'] = $supports;        
        $default_private_settings['supports'] = $supports;
        
        $post_types = array();
        
        if (  $this->_config->get_group('use_default_post_type', true ) ) {
            $default_post_type = $this->_config->get_group( 'default_post_type' );
            $post_types[$default_post_type['slug']] = $default_post_type;
        }
        
        foreach ( $post_types as $p_key => $post_type ) {
            
            //TODO create slider taxonomy
            //TODO create lists taxonomy
            
            if ( $post_type['public'] === true ) {
                $args = $default_public_settings;
                
                if ( array_key_exists( 'slug', $post_type ) ) {
                    $args['rewrite'] = array( 'slug' => $post_type['slug'] );
                }
            } else {
                $args = $default_private_settings;
            }
            
            $args['labels'] = $post_type['labels'];
            
            if ( array_key_exists( 'admin_menu_icon', $post_type ) ) {
                $args['menu_icon'] = $post_type['admin_menu_icon'];
            }
            
            register_post_type( $p_key, $args );
        }
        
        if ( is_admin() ) {
            PartnerData::get_instance()->set_config( array( 'screen' => array_keys( $post_types ) ) );
        }
        
        flush_rewrite_rules();
    }
}