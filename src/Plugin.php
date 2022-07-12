<?php declare(strict_types=1);

namespace Kids\CockpitPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capable;
use Symfony\Component\Filesystem\Filesystem;

class Plugin implements PluginInterface, Capable
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $config = $composer->getConfig();

        if ($config->get('cockpit') !== null) { return; }

        $config->getConfigSource()->addConfigSetting(
            'cockpit',
            [
                'app-groups' => [
                    'default' => ['public']
                ],
                'override-folder' => 'override-cockpit'
            ]
        );

        $pathinfo = pathinfo($config->getConfigSource()->getName());
        $homeDir = $pathinfo['dirname'];

        $overrideConfigDir = join('/', [$homeDir, 'override-cockpit', 'config']);

        $filesystem = new Filesystem();
        $filesystem->mkdir([
            $overrideConfigDir,
            join('/', [$homeDir, 'override-cockpit', 'storage', 'collections'])
        ]);

        $overrideConfigYaml = $overrideConfigDir . '/config.yaml';
        if (!$filesystem->exists($overrideConfigYaml))
        {
            $filesystem->dumpFile(
                $overrideConfigYaml,
                'app.name: Auto-generated Backend'
            );
        }
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => 'Kids\CockpitPlugin\CommandProvider',
        ];
    }
}
