<?php

namespace App\Command;

use App\BookUser\BookClient;
use App\DTO\Book;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-book-service',
    description: 'Tests soap client for the book service',
)]
class TestBookServiceCommand extends Command
{
    public function __construct(
//        private BookClient $bookClient,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $bookClient = new BookClient();

            $bookId = array("5409");
            $book = new Book('Rust Development', 2010);


            $io->info("Book Year\t: " . $bookClient->getBookYr($bookId));
            $io->info("Book Details\t: " . $bookClient->getBookDetails($book));

            $io->success("Done");

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;

        }

    }
}
