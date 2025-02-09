<?php

namespace App\Command;

use App\Soap\BookService;
use PHP2WSDL\PHPClass2WSDL;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-wsdl',
    description: 'Generates wsdl from a book service',
)]
class GenerateWsdlCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $serviceURI = 'http://symf-web/book-service';
        $wsdlGenerator = new PHPClass2WSDL(BookService::class, $serviceURI);


        $wsdlGenerator->generateWSDL(true);

        $wsdlGenerator->generateWSDL();


        $wsdlGenerator->save('book.wsdl');

        $io->success('Wsdl was created');

        return Command::SUCCESS;
    }
}
