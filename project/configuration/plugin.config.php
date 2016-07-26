<?php
namespace Wizzaro\Partners;

return array(
    'controllers' => array(
    ),
    'configuration' => array(
        'path' => array(
            'main_file' => WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'wizzaro-partners' . DIRECTORY_SEPARATOR . 'wizzaro-partners.php'
        ),
        'view' => array(
            'templates_path' => 'src' . DIRECTORY_SEPARATOR . 'View'
        ),
        'languages' => array(
            'domain' => 'wizzaro-partners'
        )
    )
);