<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf529449e9e3d82be10e208ef01f6ded3
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpOffice\\PhpWord\\' => 18,
            'PhpOffice\\Math\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'PhpOffice\\Math\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/math/src/Math',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DB' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'DBTransaction' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDB' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBException' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBParsedQuery' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBWalk' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroORM' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMColumn' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMException' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMScope' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMTable' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'WhereClause' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf529449e9e3d82be10e208ef01f6ded3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf529449e9e3d82be10e208ef01f6ded3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf529449e9e3d82be10e208ef01f6ded3::$classMap;

        }, null, ClassLoader::class);
    }
}
