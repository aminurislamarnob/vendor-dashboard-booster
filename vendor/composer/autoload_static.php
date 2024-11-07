<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb1b6b7eb719b242af851f9e94abf4a8b
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PluginizeLab\\DokanCustomers\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PluginizeLab\\DokanCustomers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PluginizeLab\\DokanCustomers\\Assets' => __DIR__ . '/../..' . '/includes/Assets.php',
        'PluginizeLab\\DokanCustomers\\DokanCustomers' => __DIR__ . '/../..' . '/includes/DokanCustomers.php',
        'PluginizeLab\\DokanCustomers\\ManageCustomers' => __DIR__ . '/../..' . '/includes/ManageCustomers.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb1b6b7eb719b242af851f9e94abf4a8b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb1b6b7eb719b242af851f9e94abf4a8b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb1b6b7eb719b242af851f9e94abf4a8b::$classMap;

        }, null, ClassLoader::class);
    }
}
