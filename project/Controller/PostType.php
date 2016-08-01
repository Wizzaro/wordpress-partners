<?php
namespace Wizzaro\Partners\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;
use Wizzaro\Partners\Setting\SettingsPage;
use Wizzaro\Partners\Setting\OptionFormTab\PartnerData as PartnerDataOptionTab;
use Wizzaro\Partners\Component\Metabox\PartnerData;
use Wizzaro\Partners\Collections\PostTypes as PostTypesCollection;
use Wizzaro\Partners\Entity\PostType as PostTypeEntity;
use Wizzaro\Partners\Entity\PostMeta\PartnerData as PartnerDataEntity;
use Wizzaro\Partners\Config\PluginConfig;

class PostType extends AbstractPluginController {
    
    public function init() {
        add_action( 'init', array( $this, 'action_register_post_types' ), 0 );
    }
    
    public function init_front() {
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_init_display_partner_data' ) );
    }
    
    public function init_admin() {
        PartnerData::create();
        
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_set_post_data_metabox_screens' ) );
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_create_settings_pages_for_post_types' ) );
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
        
        $post_types_settings = array();
        
        if (  $this->_config->get_group('use_default_post_type', true ) ) {
            $default_post_type_setting = $this->_config->get_group( 'default_post_type' );
            $post_types_settings[$default_post_type_setting['post_type']] = $default_post_type_setting;
        }
        
        $post_types_settings = array_merge( $post_types_settings, $this->_config->get_group( 'post_types', array() ) );
        
        do_action( 'wizzaro_partners_before_register_post_types' );

        foreach ( $post_types_settings as $post_type => $post_type_settings ) {
            
            $post_type_instance = new PostTypeEntity( $post_type_settings );
            
            //TODO create slider taxonomy
            //TODO create lists taxonomy
            
            if ( $post_type_settings['public'] === true ) {
                $args = $default_public_settings;
                
                if ( array_key_exists( 'slug', $post_type_settings ) ) {
                    $args['rewrite'] = array( 'slug' => $post_type_settings['slug'] );
                }
                
            } else {
                $args = $default_private_settings;
            }
            
            $args['labels'] = $post_type_settings['labels'];
            
            $supports = array( 'title', 'revisions' );
        
            if ( $post_type_settings['supports']['logo'] === true ) {
                array_push( $supports, 'thumbnail' );
            }
            
            if ( $post_type_settings['supports']['content'] === true ) {
                array_push( $supports, 'editor' );
            }
        
            $args['supports'] = $supports;
            
            if ( array_key_exists( 'admin_menu_icon', $post_type_settings ) ) {
                $args['menu_icon'] = $post_type_settings['admin_menu_icon'];
            }

            PostTypesCollection::get_instance()->add_post_type( $post_type, $post_type_instance );
            
            register_post_type( $post_type, $args );
        }

        do_action( 'wizzaro_partners_after_register_post_types', array_keys( $post_types_settings ) );
        
        flush_rewrite_rules();
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for front
    
    public function action_init_display_partner_data( $post_types ) {
        add_filter( 'the_content', array( $this, 'filter_add_partner_data_to_content' ) );
        
        foreach( $post_types as $post_type ) {
            add_shortcode( $post_type . '-data', array( $this, 'render_shordcode_partner_data') );
        }
    }
    
    public function filter_add_partner_data_to_content( $content ) {
        $post = get_post();
        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );
        
        if ( $post_type ) {
            switch( $post_type->get_option_instance()->get_option( $post->post_type . '-partner-data', 'display_place' ) ) {
                case 'before':
                    $content = $this->get_view( 'partner-data', array(
                            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
                            'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
                            'partner_data' => new PartnerDataEntity( $post->ID ) 
                        )
                     ) . $content;
                    break;
                case 'after':
                    $content = $content . $this->get_view( 'partner-data', array(
                            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
                            'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
                            'partner_data' => new PartnerDataEntity( $post->ID ) 
                        )
                     );
                    break;
            }
        }
        
        return $content;
    }
    
    public function render_shordcode_partner_data() {
        $post = get_post();
        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );
        
        if ( $post_type ) {
            return $this->get_view( 'partner-data', array(
                    'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
                    'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
                    'partner_data' => new PartnerDataEntity( $post->ID ) 
                )
             );
        }
        
        return '';
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for admin
    
    public function action_set_post_data_metabox_screens( $post_types ) {
        PartnerData::get_instance()->set_config( array( 'screen' => $post_types ) );
    }
    
    public function action_create_settings_pages_for_post_types() {
        foreach( PostTypesCollection::get_instance()->get_post_types() as $post_type ) {
            if ( $post_type->get_setting('public', false) ) {
                $settings_tabs = $post_type->get_setting( 'settings_tabs' );

                new PartnerDataOptionTab( 
                    new SettingsPage( $post_type->get_setting( 'setting_page_config' ) ), 
                    $post_type->get_option_instance(),
                    $settings_tabs['partner_data']
                );
            }
        }
    }
}