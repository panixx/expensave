<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateRecurringExpenseRequest
{
    #[Assert\NotBlank]
    public int $calendarId;

    #[Assert\NotBlank]
    public string $label;

    #[Assert\NotBlank]
    public float $amount;

    public ?int $categoryId = null;

    #[Assert\NotBlank]
    public string $startDate;

    #[Assert\NotBlank]
    public string $interval;

    public ?string $endDate = null;

    public bool $isActive = true;
}
