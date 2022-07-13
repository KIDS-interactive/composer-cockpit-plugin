<?php declare(strict_types=1);

namespace Kids\CockpitPlugin;

use Symfony\Component\Filesystem\Filesystem;

class CockpitApp
{
    private $baseDir;
    private $overrideDir;
    private $vendorDir;
    private Filesystem $filesystem;

    public function __construct(string $baseDir, string $overrideDir, string $vendorDir)
    {
        $this->baseDir = $baseDir;
        $this->overrideDir = $overrideDir;
        $this->vendorDir = $vendorDir;
        $this->filesystem = new Filesystem();
    }

    public function create()
    {
        $filesystem = $this->filesystem;

        if (!$filesystem->exists($this->baseDir))
        {
            $filesystem->mkdir($this->baseDir);
        }

        $filesystem->mirror(
            $this->vendorDir . '/aheinze/cockpit',
            $this->baseDir,
            null,
            ['override' => true]
        );
        $filesystem->mirror(
            $this->overrideDir,
            $this->baseDir,
            null,
            ['override' => true]
        );
    }

    public function saveConfig()
    {
        $this->saveConfigDir('config');
        $this->saveConfigDir('storage/collections');
        $this->saveConfigDir('storage/singleton');
        $this->saveConfigDir('storage/forms');
    }

    private function saveConfigDir($dir)
    {
        $filesystem = $this->filesystem;
        if (!$filesystem->exists($this->baseDir . '/' . $dir)) { return; }
        $filesystem->mirror(
            $this->baseDir . '/' . $dir,
            $this->overrideDir . '/' . $dir,
            null,
            ['override' => true]
        );
    }
}
