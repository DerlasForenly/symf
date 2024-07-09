<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileStorageService
{
    const DEFAULT_DOWNLOADS_STORAGE_FOLDER = 'downloads';

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     *
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected LoggerInterface $logger
    ) {
        $this->filesystem = new Filesystem();
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function downloadImage(string $url): string
    {
        $response  = $this->httpClient->request('GET', $url);
        $imageData = $response->getContent();
        $parsedUrl = parse_url($url);
        $path      = $parsedUrl['path'] ?? '';
        $filename  = pathinfo($path, PATHINFO_BASENAME);
        $savePath  = self::DEFAULT_DOWNLOADS_STORAGE_FOLDER . '/' . $filename;

        $this->filesystem->dumpFile($savePath, $imageData);

        return $savePath;
    }
}
