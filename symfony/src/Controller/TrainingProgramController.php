<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrainingProgramController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * READ - список усіх програм тренувань
     *
     * @return JsonResponse
     */
    #[Route('/training-programs', name: 'get_training_programs', methods: [Request::METHOD_GET])]
    public function getTrainingPrograms(): JsonResponse
    {
        $trainingPrograms = $this->entityManager->getRepository(TrainingProgram::class)->findAll();

        return $this->json(['data' => $trainingPrograms], Response::HTTP_OK);
    }

    /**
     * READ - одна програма тренувань за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/training-programs/{id}', name: 'get_training_program_item', methods: [Request::METHOD_GET])]
    public function getTrainingProgramItem(string $id): JsonResponse
    {
        $trainingProgram = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (!$trainingProgram) {
            return $this->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $trainingProgram], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нову програму тренувань
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/training-programs', name: 'post_training_programs', methods: [Request::METHOD_POST])]
    public function createTrainingProgram(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $trainer = $this->entityManager->getRepository(Trainer::class)->find($requestData['trainerId']);

        if (!$trainer) {
            return $this->json(['data' => ['error' => 'Not found trainer by id ' . $requestData['trainerId']]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram = new TrainingProgram();
        $trainingProgram->setTrainer($trainer);
        $trainingProgram->setName($requestData['name']);
        $trainingProgram->setDescription($requestData['description'] ?? null);
        $trainingProgram->setDurationWeeks($requestData['durationWeeks']);
        $trainingProgram->setPrice($requestData['price']);

        $this->entityManager->persist($trainingProgram);
        $this->entityManager->flush();

        return $this->json(['data' => $trainingProgram], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити програму тренувань за id
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/training-programs/{id}', name: 'put_training_programs', methods: [Request::METHOD_PUT])]
    public function updateTrainingProgram(Request $request, string $id): JsonResponse
    {
        $trainingProgram = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (!$trainingProgram) {
            return $this->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $trainer = $this->entityManager->getRepository(Trainer::class)->find($requestData['trainerId']);

        if (!$trainer) {
            return $this->json(['data' => ['error' => 'Not found trainer by id ' . $requestData['trainerId']]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram->setTrainer($trainer);
        $trainingProgram->setName($requestData['name']);
        $trainingProgram->setDescription($requestData['description'] ?? null);
        $trainingProgram->setDurationWeeks($requestData['durationWeeks']);
        $trainingProgram->setPrice($requestData['price']);

        $this->entityManager->flush();

        return $this->json(['data' => $trainingProgram], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити програму тренувань за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/training-programs/{id}', name: 'delete_training_programs', methods: [Request::METHOD_DELETE])]
    public function deleteTrainingProgram(string $id): JsonResponse
    {
        $trainingProgram = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (!$trainingProgram) {
            return $this->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($trainingProgram);
        $this->entityManager->flush();

        return $this->json(['data' => 'Deleted training program by id ' . $id], Response::HTTP_OK);
    }
}
