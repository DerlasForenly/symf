<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(protected UserRepository $userRepository)
    {

    }

    #[Route('/api/users', name: 'api_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([]);
    }

    #[Route('/api/users/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        $userData = [
            'id' => $user->getId(),
            'nickname' => $user->getNickname(),
            'email' => $user->getEmail(),
        ];

        return new JsonResponse($userData);
    }

    #[Route('/api/users/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
