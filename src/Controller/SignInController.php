<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends AbstractController
{
    #[Route('/api/sign-in', name: 'api_sign_in', methods: ['POST'])]
    public function login(): JsonResponse
    {
        /**
         * @var \App\Entity\User $user
         */
        $user = $this->getUser();

        return new JsonResponse([
            'user' => $user?->getNickname(),
        ]);
    }
}