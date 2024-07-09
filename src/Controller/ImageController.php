<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ImageController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ImageRepository $imageRepository
    ) {}

    #[Route('/api/images', name: 'api_images_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $images = $this->imageRepository->findAll();
        $data = $this->serializer->serialize($images, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
        ]);
        $data = json_decode($data, true);

        return new JsonResponse($data);
    }

    #[Route('/api/images/my', name: 'api_my_images_index', methods: ['GET'])]
    public function my(): JsonResponse
    {
        $images = $this->imageRepository->findBy(['user' => $this->getUser()]);
        //$images = $this->getUser()->getImages();

        $data = $this->serializer->serialize($images, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
        ]);
        $data = json_decode($data, true);

        return new JsonResponse($data);
    }
}