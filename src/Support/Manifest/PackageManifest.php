<?php

namespace Larapie\Core\Support\Manifest;

use Illuminate\Support\Arr;
use Larapie\Core\Support\Facades\Larapie;

class PackageManifest extends \Illuminate\Foundation\PackageManifest
{
    public function packagesToIgnore()
    {
        $ignore = [];

        foreach (Larapie::getModules() as $module) {
            $ignore[] = $this->extractToIgnorePackagesFromComposerFile($module->getComposerFilePath());
        }

        foreach (Larapie::getPackages() as $package) {
            $ignore[] = $this->extractToIgnorePackagesFromComposerFile($package->getComposerFilePath());
        }

        return array_unique(array_merge(parent::packagesToIgnore(), Arr::flatten($ignore)));
    }

    protected function extractToIgnorePackagesFromComposerFile(string $composerPath)
    {
        if (!file_exists($composerPath)) {
            return [];
        }
        $composer = json_decode(file_get_contents($composerPath), true);

        return $composer['extra']['laravel']['dont-discover'] ?? [];
    }

    public function build()
    {
        return tap(parent::build(), function () {
            $this->getManifest();
        });
    }
}
