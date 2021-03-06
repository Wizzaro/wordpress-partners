<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit002b13c0324cfab4087e1128b678e111
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wizzaro\\WPFramework\\v1\\' => 23,
            'Wizzaro\\Partners\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wizzaro\\WPFramework\\v1\\' => 
        array (
            0 => __DIR__ . '/..' . '/wizzaro/wordpress-framework-v1/src',
        ),
        'Wizzaro\\Partners\\' => 
        array (
            0 => __DIR__ . '/../..' . '/project',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit002b13c0324cfab4087e1128b678e111::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit002b13c0324cfab4087e1128b678e111::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
