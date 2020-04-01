<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2f63fe0af46c44836c0e8e6dd879c405
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Melihovv\\Base64ImageDecoder\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Melihovv\\Base64ImageDecoder\\' => 
        array (
            0 => __DIR__ . '/..' . '/melihovv/base64-image-decoder/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2f63fe0af46c44836c0e8e6dd879c405::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2f63fe0af46c44836c0e8e6dd879c405::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}