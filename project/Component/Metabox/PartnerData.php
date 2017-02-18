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
        add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_style' ) );
    }

    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }

    protected function _get_metabox_config() {

        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-partner-data',
                'title' => __( 'Data', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'normal',
                'priority' => 'high'
            );
        }

        return $this->_config;
    }

    public function render( $post ) {
        $post_type = PostTypes::get_instance()->get_post_type( $post->post_type );

        wp_enqueue_script( 'wizzaro-partners-metabox-partner-data-js', PluginConfig::get_instance()->get_js_admin_url() . 'metabox-partner-data.js', array( 'jquery' ), '1.0.0' , true );

        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'post' => $post,
            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
            'post_type_option_instance' => $post_type->get_option_instance(),
            'partner_data_attributes' => $post_type->get_setting( 'partner_data_attributes' ),
            'partner_data' => new PartnerDataEntity( $post->ID )
        ) );
    }

    public function action_enqueue_style() {
        global $post;

        if ( $post->post_type && PostTypes::get_instance()->get_post_type( $post->post_type ) !== false ) {
            wp_enqueue_style( 'wizzaro-partners-metabox-partner-data', PluginConfig::get_instance()->get_css_admin_url() . 'metabox-partner-data.css' );
        }
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

            foreach ( $partner_data_attributes as $attribute_key => $attribute_settings ) {
                if( array_key_exists( $attribute_key, $new_post_data ) ) {
                    if ( array_key_exists( 'multiple', $attribute_settings) && $attribute_settings['multiple'] === true ) {
                        if ( is_array( $new_post_data[$attribute_key] ) ) {
                            $newAttributes = array();

                            foreach( $new_post_data[$attribute_key] as $attr ) {
                                if ( is_string( $attr ) && mb_strlen( $attr ) ) {
                                    $newAttributes[] = $this->filterAttribute( $attribute_settings['type'], $attr );
                                }
                            }

                            $post_data->$attribute_key = $newAttributes;
                        } elseif ( is_string( $new_post_data[$attribute_key] ) && mb_strlen( $new_post_data[$attribute_key] ) > 0 ) {
                            $post_data->$attribute_key = array( $this->filterAttribute( $attribute_settings['type'], $new_post_data[$attribute_key] ) );
                        } else {
                            $post_data->$attribute_key = array();
                        }
                    } else {
                        $post_data->$attribute_key = $this->filterAttribute( $attribute_settings['type'], $new_post_data[$attribute_key] );
                    }
                }
            }
        }

        $post_data->save();
    }

    private function filterAttribute( $type, $value ) {
        $filter_instance = Filter::get_instance();

        switch ( $type ) {
            case 'phone':
                $value = $filter_instance->filter_text( $value );
                break;
            case 'email':
                $value = sanitize_email( $value );
                break;
            case 'url':
                $value = $filter_instance->filter_url( $value );
                break;
            default:
                $value = $filter_instance->filter_text( $value );
                break;
        }

        return $value;
    }
}
