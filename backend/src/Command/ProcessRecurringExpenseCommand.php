<?php

namespace App\Command;

use App\Service\RecurringExpenseProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'expensave:process-recurring-expenses',
    description: 'Generate today\'s expenses from recurring rules',
)]
class ProcessRecurringExpensesCommand extends Command
{
    public function __construct(
        private readonly RecurringExpenseProcessor $processor
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $today = new \DateTime();
        $this->processor->processForDate($today);
        $output->writeln('Processed recurring expenses for ' . $today->format('Y-m-d'));
        return Command::SUCCESS;
    }
}
