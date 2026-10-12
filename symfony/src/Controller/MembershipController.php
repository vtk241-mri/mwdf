<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Membership;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MembershipController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * READ - список усіх абонементів
     *
     * @return JsonResponse
     */
    #[Route('/memberships', name: 'get_memberships', methods: [Request::METHOD_GET])]
    public function getMemberships(): JsonResponse
    {
        $memberships = $this->entityManager->getRepository(Membership::class)->findAll();

        return $this->json(['data' => $memberships], Response::HTTP_OK);
    }

    /**
     * READ - один абонемент за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/memberships/{id}', name: 'get_membership_item', methods: [Request::METHOD_GET])]
    public function getMembershipItem(string $id): JsonResponse
    {
        $membership = $this->entityManager->getRepository(Membership::class)->find($id);

        if (!$membership) {
            return $this->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $membership], Response::HTTP_OK);
    }

    /**
     * CREATE - створити новий абонемент (записати клієнта на програму)
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/memberships', name: 'post_memberships', methods: [Request::METHOD_POST])]
    public function createMembership(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $client = $this->entityManager->getRepository(Client::class)->find($requestData['clientId']);

        if (!$client) {
            return $this->json(['data' => ['error' => 'Not found client by id ' . $requestData['clientId']]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram = $this->entityManager->getRepository(TrainingProgram::class)->find($requestData['trainingProgramId']);

        if (!$trainingProgram) {
            return $this->json(['data' => ['error' => 'Not found training program by id ' . $requestData['trainingProgramId']]], Response::HTTP_NOT_FOUND);
        }

        $membership = new Membership();
        $membership->setClient($client);
        $membership->setTrainingProgram($trainingProgram);
        $membership->setStartDate(new \DateTimeImmutable($requestData['startDate']));
        $membership->setEndDate(new \DateTimeImmutable($requestData['endDate']));
        $membership->setStatus($requestData['status'] ?? 'active');

        $this->entityManager->persist($membership);
        $this->entityManager->flush();

        return $this->json(['data' => $membership], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити абонемент за id
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/memberships/{id}', name: 'put_memberships', methods: [Request::METHOD_PUT])]
    public function updateMembership(Request $request, string $id): JsonResponse
    {
        $membership = $this->entityManager->getRepository(Membership::class)->find($id);

        if (!$membership) {
            return $this->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $client = $this->entityManager->getRepository(Client::class)->find($requestData['clientId']);

        if (!$client) {
            return $this->json(['data' => ['error' => 'Not found client by id ' . $requestData['clientId']]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram = $this->entityManager->getRepository(TrainingProgram::class)->find($requestData['trainingProgramId']);

        if (!$trainingProgram) {
            return $this->json(['data' => ['error' => 'Not found training program by id ' . $requestData['trainingProgramId']]], Response::HTTP_NOT_FOUND);
        }

        $membership->setClient($client);
        $membership->setTrainingProgram($trainingProgram);
        $membership->setStartDate(new \DateTimeImmutable($requestData['startDate']));
        $membership->setEndDate(new \DateTimeImmutable($requestData['endDate']));
        $membership->setStatus($requestData['status'] ?? 'active');

        $this->entityManager->flush();

        return $this->json(['data' => $membership], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити абонемент за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/memberships/{id}', name: 'delete_memberships', methods: [Request::METHOD_DELETE])]
    public function deleteMembership(string $id): JsonResponse
    {
        $membership = $this->entityManager->getRepository(Membership::class)->find($id);

        if (!$membership) {
            return $this->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($membership);
        $this->entityManager->flush();

        return $this->json(['data' => 'Deleted membership by id ' . $id], Response::HTTP_OK);
    }
}
