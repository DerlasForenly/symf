<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DallEClient
{
    const BASE_URL = 'https://api.openai.com/v1/images/generations';

    public function __construct(
        protected HttpClientInterface $httpClient,
        protected LoggerInterface $logger,
        protected string $apiKey
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function generateImage(string $prompt, int $width = 1024, int $height = 1024, int $n = 1): array
    {
        $payload = [
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => $n,
            'size' => $width . 'x' . $height,
        ];

        try {
            $this->logger->info('Sending request to DALL·E API', ['payload' => $payload]);

            $response = $this->httpClient->request('POST', self::BASE_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ])->toArray();

            $this->logger->info('Received response from DALL·E API', ['response' => $response]);

            return $response;

        } catch (ClientException $exception) {
            $this->logger->error('Client error: ' . $exception->getMessage());

            throw $exception;
        } catch (Exception $exception) {
            $this->logger->error('Unexpected error: ' . $exception->getMessage());

            throw $exception;
        }
    }
}
