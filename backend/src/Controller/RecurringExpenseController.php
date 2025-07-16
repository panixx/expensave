<?php

namespace App\Controller;

use App\Entity\RecurringExpense;
use App\Entity\Calendar;
use App\Entity\Category;
use App\Dto\RecurringExpenseDto;
use App\Repository\RecurringExpenseRepository;
use App\Repository\CalendarRepository;
use App\Repository\CategoryRepository;
use App\Request\CreateRecurringExpenseRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/recurring-expenses')]
class RecurringExpenseController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RecurringExpenseRepository $repo,
        private readonly CalendarRepository $calendarRepo,
        private readonly CategoryRepository $categoryRepo,
        private readonly UserRepository $userRepo,
    ) {}

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $entities = $this->repo->findAll();
        $dtos = array_map(fn($e) => RecurringExpenseDto::fromEntity($e), $entities);
        return $this->json($dtos);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = new CreateRecurringExpenseRequest();
        foreach ($data as $k => $v) { $dto->$k = $v; }
        $errors = $validator->validate($dto);
        if (count($errors)) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $calendar = $this->calendarRepo->find($dto->calendarId);
        $user = $this->getUser();
        $category = $dto->categoryId ? $this->categoryRepo->find($dto->categoryId) : null;

        $entity = new RecurringExpense();
        $entity->setCalendar($calendar)
            ->setUser($user)
            ->setLabel($dto->label)
            ->setAmount($dto->amount)
            ->setCategory($category)
            ->setStartDate(new \DateTime($dto->startDate))
            ->setInterval($dto->interval)
            ->setEndDate($dto->endDate ? new \DateTime($dto->endDate) : null)
            ->setIsActive($dto->isActive);

        $this->em->persist($entity);
        $this->em->flush();

        return $this->json(RecurringExpenseDto::fromEntity($entity), 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entity = $this->repo->find($id);
        if (!$entity) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        // ... similar to create, set fields as needed
        // save and return updated DTO
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $entity = $this->repo->find($id);
        if (!$entity) return $this->json(['error' => 'Not found'], 404);
        $this->em->remove($entity);
        $this->em->flush();
        return $this->json(['status' => 'deleted']);
    }
}
