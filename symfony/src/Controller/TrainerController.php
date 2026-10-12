<?php

namespace App\Controller;

use App\Entity\Trainer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrainerController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * READ - список усіх тренерів
     *
     * @return JsonResponse
     */
    #[Route('/trainers', name: 'get_trainers', methods: [Request::METHOD_GET])]
    public function getTrainers(): JsonResponse
    {
        $trainers = $this->entityManager->getRepository(Trainer::class)->findAll();

        return $this->json(['data' => $trainers], Response::HTTP_OK);
    }

    /**
     * READ - один тренер за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/trainers/{id}', name: 'get_trainer_item', methods: [Request::METHOD_GET])]
    public function getTrainerItem(string $id): JsonResponse
    {
        $trainer = $this->entityManager->getRepository(Trainer::class)->find($id);

        if (!$trainer) {
            return $this->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $trainer], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нового тренера
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/trainers', name: 'post_trainers', methods: [Request::METHOD_POST])]
    public function createTrainer(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $trainer = new Trainer();
        $trainer->setFirstName($requestData['firstName']);
        $trainer->setLastName($requestData['lastName']);
        $trainer->setEmail($requestData['email']);
        $trainer->setPhone($requestData['phone'] ?? null);
        $trainer->setSpecialization($requestData['specialization']);

        $this->entityManager->persist($trainer);
        $this->entityManager->flush();

        return $this->json(['data' => $trainer], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити тренера за id
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/trainers/{id}', name: 'put_trainers', methods: [Request::METHOD_PUT])]
    public function updateTrainer(Request $request, string $id): JsonResponse
    {
        $trainer = $this->entityManager->getRepository(Trainer::class)->find($id);

        if (!$trainer) {
            return $this->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $trainer->setFirstName($requestData['firstName']);
        $trainer->setLastName($requestData['lastName']);
        $trainer->setEmail($requestData['email']);
        $trainer->setPhone($requestData['phone'] ?? null);
        $trainer->setSpecialization($requestData['specialization']);

        $this->entityManager->flush();

        return $this->json(['data' => $trainer], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити тренера за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/trainers/{id}', name: 'delete_trainers', methods: [Request::METHOD_DELETE])]
    public function deleteTrainer(string $id): JsonResponse
    {
        $trainer = $this->entityManager->getRepository(Trainer::class)->find($id);

        if (!$trainer) {
            return $this->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($trainer);
        $this->entityManager->flush();

        return $this->json(['data' => 'Deleted trainer by id ' . $id], Response::HTTP_OK);
    }
}
