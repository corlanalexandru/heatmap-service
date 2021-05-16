<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helper\JunkDataProvider;

class JunkDataProviderCommand extends Command
{
    private $junkDataProvider;
    public function __construct(JunkDataProvider $junkDataProvider)
    {
        parent::__construct();
        $this->junkDataProvider = $junkDataProvider;
    }

    protected function configure()
    {
        $this->setName('database:provide:junk:data')->setDescription('Fill database with junk data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->junkDataProvider->provideData();
        $io->success('Database entries updated!');
        return 0;
    }

}