<?php

namespace Bone\VendorDev\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class VendorDevCommand extends Command
{
    /** @var array  */
    private $results = [];

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
            $output->writeln('Entering <comment>vendor/' . $vendor .'</comment>.');
            $folders = glob('vendor/' . $vendor . '/*');
            $output->writeln('Found ' . count($folders) .' projects.');
            $output->writeln('');
            chdir("vendor/$vendor");

            foreach ($folders as $folder) {
                $folder = str_replace('vendor/', '', $folder);
                $output->writeln('Checking <info>' .  $folder . '</info>');
                $this->checkFolder($folder, $vendor, $output);
            }

            if (count($this->results)) {
                $output->writeln('');
                $output->writeln('The following packages have been changed:');
                $output->writeln('');

                foreach ($this->results as $result) {
                    $output->writeln("<comment>$result</comment>");
                }
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
        $folder = str_replace( $vendor . '/', '', $folder);

        if (!file_exists($folder . '/.git')) {
            $output->writeln('No Git folder found. Cloning into temporary folder');
            $process = Process::fromShellCommandline("git clone https://github.com/$vendor/$folder xxx");
            $process->run();
            $output->writeln('cloning complete, moving .git folder in place');
            $process = Process::fromShellCommandline("mv xxx/.git $folder");
            $process->run();
            $output->writeln('removing temp folder');
            $process = Process::fromShellCommandline("rm -fr xxx");
            $process->run();
        }

        chdir($folder);
        $process = Process::fromShellCommandline("git status");
        $process->run();
        $result = $process->getOutput();

        if (!strstr($result, 'nothing to commit, working tree clean')) {
            $this->results[] = $vendor . '/' . $folder;
        }

        chdir('..');
    }
}