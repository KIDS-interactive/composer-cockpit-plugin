<?php declare(strict_types=1);

namespace Kids\CockpitPlugin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Composer\Command\BaseCommand;

class SaveCockpitConfigCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('save-cockpit-config')
            ->addArgument('app-dir', InputArgument::OPTIONAL, 'Directory of cockpit application')
            ->setDescription('Saves the config of cockpit into the override dir.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composerConfig = $this->getComposer()->getConfig();
        $config = $composerConfig->get('cockpit');
        $appGroups = $config['app-groups'];
        $appDir = $input->getArgument('app-dir');
        if (!$appDir)
        {
            $appDir = array_values($appGroups)[0][0];
        }

        $vendorDir = $composerConfig->get('vendor-dir');
        $pathinfo = pathinfo($composerConfig->getConfigSource()->getName());
        $homeDir = $pathinfo['dirname'];
        $overrideDir = $homeDir . '/' . $config['override-dir'];

        (new CockpitApp($homeDir . '/' . $appDir, $overrideDir, $vendorDir))->saveConfig();
        $output->writeln('Config of cockpit in "' . $appDir . '" saved.');
        $output->writeln('Please make your commit!');

        return 1;
    }
}
