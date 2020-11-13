<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c00a51228843eddf9b9b7fdfa3c900b
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c00a51228843eddf9b9b7fdfa3c900b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c00a51228843eddf9b9b7fdfa3c900b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}