<?php

namespace Bone\VendorDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class VendorDevCommand extends Command
{
    public function configure()
    {
        parent::configure();
        $this->setDescription('Checks for changes in your vendor folder code');
        $this->setHelp('Pass in the vendor name to check');
        $this->addArgument('vendor', InputArgument::REQUIRED, 'The name of the vendor folder');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $vendor = $input->getArgument('vendor');
        if (file_exists('vendor/' . $vendor)) {
            $output->writeln('Entering vendor/' . $vendor .' directory.');
            $folders = glob('vendor/' . $vendor . '/*');
            $output->writeln('Found ' . count($folders) .' projects.');
            $output->writeln('');

            foreach ($folders as $folder) {
                $output->writeln('Checking ' .  $folder . ' directory');
                $this->checkFolder($folder, $vendor, $output);
                $output->writeln('');
            }

        } else {
            $output->writeln('No vendor called ' . $vendor .' exists');
        }

        return 0;
    }

    /**
     * @param string $folder
     * @param string $vendor
     * @param OutputInterface $output
     */
    private function checkFolder(string $folder, string $vendor, OutputInterface $output): void
    {
        if (file_exists($folder . '/.git')) {
            $output->writeln('Git folder found.');
        } else {
            $output->writeln('No Git folder found. Cloning into temporary folder');
            $process = new Process(['cd', 'vendor/' . $vendor]);
            $process->run();
            $output->writeln($process);
            $process = new Process(['ls', '-la']);
            $process->run();
            $output->writeln('here we are');
        }
    }
}