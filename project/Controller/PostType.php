<?php
namespace Wizzaro\Partners\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;
use Wizzaro\Partners\Setting\SettingsPage;
use Wizzaro\Partners\Setting\OptionFormTab\PartnerData as PartnerDataOptionTab;
use Wizzaro\Partners\Component\Metabox\PartnerData;
use Wizzaro\Partners\Collections\PostTypes as PostTypesCollection;
use Wizzaro\Partners\Entity\PostType as PostTypeEntity;
use Wizzaro\Partners\Entity\PostMeta\PartnerData as PartnerDataEntity;
use \Exception;

class PostType extends AbstractPluginController {

    public function init() {
        add_action( 'init', array( $this, 'action_register_post_types' ), 0 );
    }

    public function init_front() {
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_init_display_partner_data' ) );
    }

    public function init_admin() {
        //PartnerData::create();

        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_set_post_data_metabox_screens' ) );
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_create_settings_pages_for_post_types' ) );

        add_action( 'wp_ajax_' . $this->_config->get( 'ajax_actions', 'metabox_elements_sync' ), array( $this, 'ajax_action_elements_sync' ) );

        add_action( 'save_post', array( $this, 'reset_partner_data_view_cache' ), 10, 2 );
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for all

    public function action_register_post_types() {
        $languages_domain = $this->_config->get( 'languages', 'domain' );

        $default_public_settings = array (
            'public'              => true,
            'has_archive'         => true,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );

        $default_private_settings = array(
            'public'              => false,
            'has_archive'         => false,
            'supports'            => array( 'title', 'revisions', 'thumbnail' ),
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
        $post_types_collection = PostTypesCollection::get_instance();

        do_action( 'wizzaro_partners_before_register_post_types' );

        foreach ( $post_types_settings as $post_type => $post_type_settings ) {
            $post_type_instance = new PostTypeEntity( $post_type_settings );

            if ( array_key_exists( 'register', $post_type_settings) && $post_type_settings['register'] === false ) {
                $post_types_collection->add_post_type( $post_type, $post_type_instance );
            } else {
                $is_public = ( array_key_exists( 'public', $post_type_settings['args'] ) && $post_type_settings['args']['public'] === true );

                if ( ! $is_public ) {
                    $args = array_merge( $default_private_settings, $post_type_settings['args'] );
                } else {
                    $args = array_merge( $default_public_settings, $post_type_settings['args'] );
                }

                if ( array_key_exists( 'taxonomies', $post_type_settings ) ) {
                    $args['taxonomies'] = array_keys( $post_type_settings['taxonomies'] );

                    foreach ( $post_type_settings['taxonomies'] as $tax_name => $tax_settings ) {
                        $this->register_taxonomy( $tax_name, $post_type, $tax_settings, $is_public );
                    }
                }

                $post_types_collection->add_post_type( $post_type, $post_type_instance );

                register_post_type( $post_type, $args );
            }
        }

        do_action( 'wizzaro_partners_after_register_post_types', array_keys( $post_types_settings ) );

        flush_rewrite_rules();
    }

    private function register_taxonomy( $taxonomy, $object_type, array $args, $public = true ) {

        $taxonomy_args = array(
            'labels'              => $args['labels'],
            'public'              => $public,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_quick_edit'  => true,
            'show_admin_column'   => true,
            'hierarchical'        => true
        );

        if ( array_key_exists( 'slug', $args ) ) {
            $taxonomy_args['rewrite'] = array( 'slug' => $args['slug'] );
        }

        if ( array_key_exists( 'hierarchical', $args ) ) {
            $taxonomy_args['hierarchical'] = $args['hierarchical'];
        }

        register_taxonomy( $taxonomy, $object_type, $taxonomy_args );
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for front

    public function action_init_display_partner_data( $post_types ) {
        add_filter( 'the_content', array( $this, 'filter_add_partner_data_to_content' ) );

        foreach( $post_types as $post_type ) {
            add_shortcode( $post_type . '-data', array( $this, 'render_shordcode_partner_data') );
        }
    }

    private function get_partner_data_view( $post ) {
        $post = get_post();
        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );

        $view = false;

        if ( $post_type ) {
            //check is data exist in cahce
            $view = wp_cache_get( 'wizzaro_partners_partner_data', $post->post_type . '-' . $post->ID );

            if ( ! $view ) {
                $attrs = $post_type->get_setting( 'partner_data_attributes' );

                if ( is_array( $attrs ) && count( $attrs ) > 0 ) {
                    $view_data = array(
                        'languages_domain' => $this->_config->get( 'languages', 'domain' ),
                        'partner_data_attributes' => $attrs,
                        'partner_data' => new PartnerDataEntity( $post->ID )
                    );

                    if ( $this->is_themes_view_exist( 'partner-data-' . $post->post_type ) ) {
                        $view = $this->get_themes_view( 'partner-data-' . $post->post_type, $view_data );
                    } elseif ( $this->is_themes_view_exist( 'partner-data' ) ) {
                        $view = $this->get_themes_view( 'partner-data', $view_data );
                    } else {
                        $view = $this->get_view( 'partner-data', $view_data );
                    }
                } else {
                    $view = '';
                }

                wp_cache_set( 'wizzaro_partners_partner_data', $view , $post->post_type . '-' . $post->ID );
            }
        }

        return $view;
    }

    public function filter_add_partner_data_to_content( $content ) {
        $post = get_post();
        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );

        if ( $post_type ) {
            $view = $this->get_partner_data_view( $post );

            if ( $view ) {
                switch( $post_type->get_option_instance()->get_option( $post->post_type . '-partner-data', 'display_place' ) ) {
                    case 'before':
                        $content = $view . $content;
                        break;
                    case 'after':
                        $content = $content . $view;
                        break;
                }
            }
        }

        return $content;
    }

    public function render_shordcode_partner_data() {
        $post = get_post();
        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );

        if ( $post_type ) {

            $view = $this->get_partner_data_view( $post );

            if ( $view ) {
                return $view;
            }
        }

        return '';
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for admin

    public function reset_partner_data_view_cache( $post_id, $post ) {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        $post_type = PostTypesCollection::get_instance()->get_post_type( $post->post_type );

        if ( $post_type ) {

            $view_data = array(
                'languages_domain' => $this->_config->get( 'languages', 'domain' ),
                'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
                'partner_data' => new PartnerDataEntity( $post->ID )
            );

            if ( $this->is_themes_view_exist( 'partner-data-' . $post->post_type ) ) {
                $view = $this->get_themes_view( 'partner-data-' . $post->post_type, $view_data );
            } elseif ( $this->is_themes_view_exist( 'partner-data' ) ) {
                $view = $this->get_themes_view( 'partner-data', $view_data );
            } else {
                $view = $this->get_view( 'partner-data', $view_data );
            }

            //save view in cache
            wp_cache_set( 'wizzaro_partners_partner_data', $view , $post->post_type . '-' . $post->ID );
        }
    }

    public function action_set_post_data_metabox_screens( $post_types ) {

        $screens = array();

        foreach( PostTypesCollection::get_instance()->get_post_types() as $post_type_key => $post_type ) {
            $attrs = $post_type->get_setting( 'partner_data_attributes', array() );

            if ( is_array( $attrs ) && count( $attrs ) > 0 ) {
                array_push( $screens, $post_type_key );
            }
        }

        if ( count( $screens ) > 0 ) {
            PartnerData::get_instance()->set_config( array( 'screen' => $screens ) );
        }
    }

    public function action_create_settings_pages_for_post_types() {
        foreach( PostTypesCollection::get_instance()->get_post_types() as $post_type ) {
            if ( $post_type->get_setting('public', false) ) {
                $attrs = $post_type->get_setting( 'partner_data_attributes', array() );
                if ( is_array( $attrs ) && count( $attrs ) > 0 ) {
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

    public function ajax_action_elements_sync() {
        $response = array( 'status' => false );

        $languages_domain = $this->_config->get( 'languages', 'domain' );

        try {
            if( ! is_admin() || ! isset( $_POST['nounce'] ) || ! wp_verify_nonce( $_POST['nounce'], 'wizzaro_partners_elements_sync_nonce' ) || ! isset( $_POST['elements_post_type'] ) ) {
                throw new Exception( __( 'Error during download elements', $languages_domain ) );
            }

            $post_type = PostTypesCollection::get_instance()->get_post_type( $_POST['elements_post_type'] );

            if ( ! $post_type ) {
                throw new Exception( __( 'Unknown post type', $languages_domain ) );
            }


            $args = array(
                'posts_per_page' => -1,
                'post_type' => $post_type->get_setting( 'post_type' ),
                'orderby' => 'title',
                'order' => 'ASC'
            );

            $elements = get_posts( $args );

            $response['status'] = true;
            $response['elements'] = array();

            foreach( $elements as $element ) {
                array_push( $response['elements'], array(
                    'id' => (string) $element->ID,
                    'name' => esc_attr( $element->post_title ),
                    'image_src' => wp_get_attachment_url( get_post_thumbnail_id( $element->ID ) )
                ));
            }
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }

        wp_send_json( $response );
        die();
    }
}
