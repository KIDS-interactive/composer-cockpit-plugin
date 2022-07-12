<?php declare(strict_types=1);

namespace Kids\CockpitPlugin;

use Symfony\Component\Filesystem\Filesystem;

class CockpitApp
{
    private $baseDir;
    private $overrideDir;
    private $vendorDir;

    public function __construct(string $baseDir, string $overrideDir, string $vendorDir)
    {
        $this->baseDir = $baseDir;
        $this->overrideDir = $overrideDir;
        $this->vendorDir = $vendorDir;
    }

    public function create()
    {
        $filesystem = new Filesystem();

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
}
