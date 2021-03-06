<?php

namespace PugBundle\Command;

use Pug\Pug;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssetsPublishCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('assets:publish')
            ->setDescription('Export your assets in the web directory.');
    }

    protected function cacheTemplates(Pug $pug)
    {
        $success = 0;
        $errors = 0;
        $directories = [];
        foreach ($pug->getOption('assetDirectory') as $assetDirectory) {
            $viewDirectory = $assetDirectory . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
            if (is_dir($viewDirectory)) {
                $directories[] = $viewDirectory;
                $data = $pug->cacheDirectory($viewDirectory);
                $success += $data[0];
                $errors += $data[1];
            }
        }

        return [$directories, $success, $errors];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($directories, $success, $errors) = $this->cacheTemplates(
            $this->getContainer()->get('templating.engine.pug')->getEngine()
        );
        $count = count($directories);
        $output->writeln($count . ' ' . ($count === 1 ? 'directory' : 'directories') . ' scanned: ' . implode(', ', $directories) . '.');
        $output->writeln($success . ' templates cached.');
        $output->writeln($errors . ' templates failed to be cached.');
    }
}
