<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * Реєстрація - новий користувач завжди отримує роль Client
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/register', name: 'api_register', methods: [Request::METHOD_POST])]
    public function register(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (empty($requestData['email']) || empty($requestData['password'])) {
            return $this->json(['data' => ['error' => 'Email and password are required']], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requestData['email']]);

        if ($existingUser) {
            return $this->json(['data' => ['error' => 'User already exists']], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($requestData['email']);
        $user->setRoles([User::ROLE_CLIENT]);
        $user->setPassword($this->passwordHasher->hashPassword($user, $requestData['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['data' => $user], Response::HTTP_CREATED);
    }

    /**
     * Логін - обробляє json_login у security.yaml, повертає JWT токен
     *
     * @return void
     */
    #[Route('/api/login', name: 'api_login', methods: [Request::METHOD_POST])]
    public function login(): void
    {
        throw new \LogicException('This code should never be reached.');
    }

    /**
     * Поточний користувач за токеном
     *
     * @return JsonResponse
     */
    #[Route('/api/me', name: 'api_me', methods: [Request::METHOD_GET])]
    public function me(): JsonResponse
    {
        return $this->json(['data' => $this->getUser()], Response::HTTP_OK);
    }
}
