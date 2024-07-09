<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    /**
     * @param \App\Repository\UserRepository $userRepository
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected UserPasswordHasherInterface $passwordHasher,
        protected ValidatorInterface $validator
    ) {}

    #[Route('/api/sign-up', name: 'api_sign_up', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setNickname($data['nickname']);
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $data['password']
        ));


        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            [
                'message' => 'User registered successfully'
            ],
            Response::HTTP_CREATED
        );
    }
}