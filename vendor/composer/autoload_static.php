<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4cca3fad3f3f961f03bb6267df6c22cc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4cca3fad3f3f961f03bb6267df6c22cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4cca3fad3f3f961f03bb6267df6c22cc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
