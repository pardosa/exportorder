<?php namespace Src;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Src\Command;

class Convert extends Command
{
    
    public function configure()
    {
        $this -> setName('convert')
            -> setDescription('Download JSON file and convert to CSV.')
            -> setHelp('This command allows you to download and convert file...')
            -> addArgument('outputfile', InputArgument::REQUIRED, 'The name of the CSV file');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->convertJSON($input, $output);
    }
}