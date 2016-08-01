<?php
namespace Wizzaro\Partners;

use Wizzaro\Partners\Texts\PluginTexts;

return array(
    'controllers' => array(
        'Wizzaro\Partners\Controller\PostType'
    ),
    'configuration' => array(
        'path' => array(
            'main_file' => WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'wizzaro-partners' . DIRECTORY_SEPARATOR . 'wizzaro-partners.php'
        ),
        'view' => array(
            'templates_path' => 'project' . DIRECTORY_SEPARATOR . 'View'
        ),
        'languages' => array(
            'domain' => 'wizzaro-partners'
        ),
        'default_post_type' => array(
            'post_type' => 'wizzaro-partners',
            'slug' => 'partners',
            'labels'=> array(
                'name'                  => __('Partners', 'wizzaro-partners'),
                'singular_name'         => __('Partner', 'wizzaro-partners'),
                'menu_name'             => __('Partners', 'wizzaro-partners'),
                'add_new'               => __( 'Add Partner', 'wizzaro-partners' ),
                'add_new_item'          => __( 'Add New Partner', 'wizzaro-partners' ),
                'edit'                  => __( 'Edit Partner', 'wizzaro-partners' ),
                'edit_item'             => __( 'Edit Partner', 'wizzaro-partners' ),
                'new_item'              => __( 'New Partner', 'wizzaro-partners' ),
                'not_found'             => __( 'No Partners found', 'wizzaro-partners' ),
                'not_found_in_trash'    => __( 'No Partners found in trash', 'wizzaro-partners' ),
                'featured_image'        => __( 'Logo', 'wizzaro-partners' ),
                'set_featured_image'    => __( 'Set logo', 'wizzaro-partners' ),
                'remove_featured_image' => __( 'Remove logo', 'wizzaro-partners' ),
                'use_featured_image'    => __( 'Use as logo', 'wizzaro-partners' ),
            ),
            'public' => true,
            'use_sliders' => true,
            'use_lists' => true,
            'admin_menu_icon' => 'dashicons-groups',
            'setting_page_config' => array(
                'page_title' => __('Partners Settings', 'wizzaro-partners'),
                'menu_title' => __('Partners Settings', 'wizzaro-partners'),
                'menu_slug' => 'wizzaro-partners-settings'
            ),
            'settings_tabs' => array(
                'partner_data' => array(
                    'name' => __( 'Partner Data', 'wizzaro-partners' ),
                    'slug' => 'partner-data'
                )
            ),
            'supports' => array(
                'logo' => true,
                'content' => true,
            ),
            'partner_data_attributes' => array(
                /*'position' => array(
                    'use' => true,
                    'type' => 'text',
                    'title' => 'Position',
                    'placeholder' => 'Add position'
                ),*/
                'address' => array(
                    'use' => true,
                    'type' => 'address',
                    'title' => __('Address', 'wizzaro-partners'),
                    'placeholder' =>  __('Add address', 'wizzaro-partners')
                ),
                'phone' => array(
                    'use' => true,
                    'type' => 'phone',
                    'title' => __('Phone', 'wizzaro-partners'),
                    'placeholder' =>  __('Add phone', 'wizzaro-partners')
                ),
                'email' => array(
                    'use' => true,
                    'type' => 'email',
                    'title' => __('E-mail', 'wizzaro-partners'),
                    'placeholder' =>  __('Add e-mail', 'wizzaro-partners')
                ),
                'website_url' => array(
                    'use' => true,
                    'type' => 'url',
                    'title' => __('Website URL', 'wizzaro-partners'),
                    'placeholder' =>  __('Add website URL', 'wizzaro-partners')
                ),
            )
        )
    )
);