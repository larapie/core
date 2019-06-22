<?php

namespace Larapie\Core\Cache;

class BootstrapCache
{
    public static function get(): ?array
    {
        if (!file_exists(self::getCachePath())) {
            return null;
        }

        return include self::getCachePath();
    }

    public static function put($data): void
    {
        if (self::cacheIsWriteable()) {
            file_put_contents(self::getCachePath(), '<?php return '.var_export($data, true).';');
        }
    }

    public static function cacheIsWriteable()
    {
        return is_writable(dirname(self::getCachePath()));
    }

    public static function forget()
    {
        unlink(self::getCachePath());
    }

    public static function exists(): bool
    {
        return file_exists(self::getCachePath());
    }

    protected static function getCachePath()
    {
        return app()->bootstrapPath().config('larapie.bootstrap_path');
    }
}
