<?php declare(strict_types=1);

namespace Kids\CockpitPlugin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Composer\Command\BaseCommand;

class CreateCockpitCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('create-cockpit')
            ->addArgument('app-group', InputArgument::OPTIONAL, 'Key of app group', 'default')
            ->setDescription('Creates a group of Cockpit applications.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composerConfig = $this->getComposer()->getConfig();
        $config = $composerConfig->get('cockpit');
        $appGroups = $config['app-groups'];
        $appGroupKey = $input->getArgument('app-group');

        if (key_exists($appGroupKey, $appGroups) && is_array($appGroups[$appGroupKey]))
        {
            $appGroup = $appGroups[$appGroupKey];
            $vendorDir = $composerConfig->get('vendor-dir');
            $pathinfo = pathinfo($composerConfig->getConfigSource()->getName());
            $homeDir = $pathinfo['dirname'];
            $overrideDir = $homeDir . '/' . $config['override-folder'];

            foreach ($appGroup as $folder)
            {
                (new CockpitApp($homeDir . '/' . $folder, $overrideDir, $vendorDir))->create();
            }
        }
        else
        {
            $output->writeln('App group "' . $appGroupKey . '" is not configured.');
        }
        return 1;
    }
}
