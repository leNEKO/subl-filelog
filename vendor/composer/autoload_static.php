<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe06a652e24bd1d303aca701f9324c91
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Eluceo\\iCal\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Eluceo\\iCal\\' => 
        array (
            0 => __DIR__ . '/..' . '/eluceo/ical/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe06a652e24bd1d303aca701f9324c91::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe06a652e24bd1d303aca701f9324c91::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}