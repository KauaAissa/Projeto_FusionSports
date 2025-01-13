<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitfe3178413c3e28fc8ea2f9b9ef7c01c9
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitfe3178413c3e28fc8ea2f9b9ef7c01c9', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitfe3178413c3e28fc8ea2f9b9ef7c01c9', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitfe3178413c3e28fc8ea2f9b9ef7c01c9::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
