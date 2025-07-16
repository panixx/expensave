<?php

namespace App\Dto;

use App\Entity\RecurringExpense;

class RecurringExpenseDto
{
    public int $id;
    public int $userId;
    public int $calendarId;
    public string $label;
    public float $amount;
    public ?int $categoryId;
    public string $startDate;
    public string $interval;
    public ?string $endDate;
    public bool $isActive;

    public static function fromEntity(RecurringExpense $entity): self
    {
        $dto = new self();
        $dto->id = $entity->getId();
        $dto->userId = $entity->getUser()->getId();
        $dto->calendarId = $entity->getCalendar()->getId();
        $dto->label = $entity->getLabel();
        $dto->amount = $entity->getAmount();
        $dto->categoryId = $entity->getCategory()?->getId();
        $dto->startDate = $entity->getStartDate()->format('Y-m-d');
        $dto->interval = $entity->getInterval();
        $dto->endDate = $entity->getEndDate()?->format('Y-m-d');
        $dto->isActive = $entity->isActive();
        return $dto;
    }
}
