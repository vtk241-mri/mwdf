<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * READ - список усіх клієнтів
     *
     * @return JsonResponse
     */
    #[Route('/clients', name: 'get_clients', methods: [Request::METHOD_GET])]
    public function getClients(): JsonResponse
    {
        $clients = $this->entityManager->getRepository(Client::class)->findAll();

        return $this->json(['data' => $clients], Response::HTTP_OK);
    }

    /**
     * READ - один клієнт за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/clients/{id}', name: 'get_client_item', methods: [Request::METHOD_GET])]
    public function getClientItem(string $id): JsonResponse
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нового клієнта
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/clients', name: 'post_clients', methods: [Request::METHOD_POST])]
    public function createClient(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $client = new Client();
        $client->setFirstName($requestData['firstName']);
        $client->setLastName($requestData['lastName']);
        $client->setEmail($requestData['email']);
        $client->setPhone($requestData['phone'] ?? null);
        $client->setBirthDate(isset($requestData['birthDate']) ? new \DateTimeImmutable($requestData['birthDate']) : null);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->json(['data' => $client], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити клієнта за id
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/clients/{id}', name: 'put_clients', methods: [Request::METHOD_PUT])]
    public function updateClient(Request $request, string $id): JsonResponse
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $client->setFirstName($requestData['firstName']);
        $client->setLastName($requestData['lastName']);
        $client->setEmail($requestData['email']);
        $client->setPhone($requestData['phone'] ?? null);
        $client->setBirthDate(isset($requestData['birthDate']) ? new \DateTimeImmutable($requestData['birthDate']) : null);

        $this->entityManager->flush();

        return $this->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити клієнта за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/clients/{id}', name: 'delete_clients', methods: [Request::METHOD_DELETE])]
    public function deleteClient(string $id): JsonResponse
    {
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return $this->json(['data' => 'Deleted client by id ' . $id], Response::HTTP_OK);
    }
}
