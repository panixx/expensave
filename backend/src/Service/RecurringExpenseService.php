<?php

namespace App\Service;

use App\Entity\RecurringExpense;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Repository\RecurringExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;

class RecurringExpenseProcessor
{
    public function __construct(
        private readonly RecurringExpenseRepository $recurringExpenseRepository,
        private readonly ExpenseRepository $expenseRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function processForDate(\DateTime $date): void
    {
        $recurrings = $this->recurringExpenseRepository->findDueForDate($date);

        foreach ($recurrings as $recurring) {
            if (!$this->expenseExists($recurring, $date)) {
                $expense = new Expense();
                $expense->setCalendar($recurring->getCalendar());
                $expense->setUser($recurring->getUser());
                $expense->setLabel($recurring->getLabel());
                $expense->setAmount($recurring->getAmount());
                $expense->setCategory($recurring->getCategory());
                $expense->setCreatedAt(clone $date);
                $expense->setConfirmed(true);

                $this->em->persist($expense);
            }
        }

        $this->em->flush();
    }

    private function expenseExists(RecurringExpense $recurring, \DateTime $date): bool
    {
        return $this->expenseRepository->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.calendar = :calendar')
            ->andWhere('e.user = :user')
            ->andWhere('e.label = :label')
            ->andWhere('e.createdAt = :date')
            ->setParameter('calendar', $recurring->getCalendar())
            ->setParameter('user', $recurring->getUser())
            ->setParameter('label', $recurring->getLabel())
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }
}
