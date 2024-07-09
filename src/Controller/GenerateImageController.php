<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\DallEClient;
use App\Service\FileStorageService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GenerateImageController extends AbstractController
{
    /**
     * @param \App\Service\DallEClient $client
     * @param \App\Service\FileStorageService $fileStorageService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        protected DallEClient $client,
        protected FileStorageService $fileStorageService,
        protected LoggerInterface $logger,
        protected EntityManagerInterface $entityManager
    ) {}

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Route('/api/generate-image', name: 'api_generate_image', methods: ['POST'])]
    public function generateImage(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $response = $this->client->generateImage($data['prompt']);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $imageUrl = $response['data'][0]['url'] ?? '';

        try {
            $path = $this->fileStorageService->downloadImage($imageUrl);

            $image = new Image();
            $image->setPrompt($data['prompt']);
            $image->setPath($path);
            $image->setUser($this->getUser());

            $this->entityManager->persist($image);
            $this->entityManager->flush();
        } catch (FileException $e) {
            $this->logger->error('Failed to save image: ' . $e->getMessage());
        }

        return $this->render('dalle.html.twig', ['image_url' => $imageUrl]);
    }
}