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
            'slug' => 'partners',
            'labels'=> array(
                'name'                  => __('Partners', 'wizzaro-partners'),
                'singular_name'         => __('Partner', 'wizzaro-partners'),
                'rewrite'               => array('slug' => 'partners'),
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
            'admin_menu_icon' => 'dashicons-groups'
        ),
        'partner_data_attributes' => array(
            'logo' => true,
            'description' => true,
            'address' => true,
            'phone' => true,
            'email' => true,
            'website_url' => true
        )
    )
);