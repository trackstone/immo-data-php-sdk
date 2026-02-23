<?php

declare(strict_types=1);

namespace ImmoData\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use ImmoData\Exceptions\AuthenticationException;
use ImmoData\Exceptions\ForbiddenException;
use ImmoData\Exceptions\ImmoDataException;
use ImmoData\Exceptions\InsufficientCreditsException;
use ImmoData\Exceptions\NotFoundException;
use ImmoData\Exceptions\RateLimitException;
use ImmoData\Exceptions\ServerException;
use ImmoData\Exceptions\ValidationException;
use JsonException;

final class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(
        private readonly string $apiKey,
        string $baseUrl,
    ) {
        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function get(string $path, array $query = []): array
    {
        try {
            $response = $this->client->get(ltrim($path, '/'), [
                'query' => $query,
            ]);

            $body = $response->getBody()->getContents();

            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (BadResponseException $e) {
            $this->handleErrorResponse($e);
        } catch (ConnectException $e) {
            throw new ServerException(
                'Connection to Immo Data API failed: ' . $e->getMessage(),
                0,
                null,
                $e,
            );
        } catch (JsonException $e) {
            throw new ImmoDataException(
                'Failed to decode API response: ' . $e->getMessage(),
                0,
                null,
                $e,
            );
        }
    }

    private function handleErrorResponse(BadResponseException $e): never
    {
        $statusCode = $e->getResponse()->getStatusCode();
        $body = null;
        $message = 'API request failed';

        try {
            $rawBody = $e->getResponse()->getBody()->getContents();
            $body = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
            $message = $body['message'] ?? $body['error'] ?? $message;
        } catch (JsonException) {
            // Keep default message
        }

        throw match ($statusCode) {
            400 => new ValidationException($message, $statusCode, $body, $e),
            401 => new AuthenticationException($message, $statusCode, $body, $e),
            402 => new InsufficientCreditsException($message, $statusCode, $body, $e),
            403 => new ForbiddenException($message, $statusCode, $body, $e),
            404 => new NotFoundException($message, $statusCode, $body, $e),
            429 => new RateLimitException($message, $statusCode, $body, $e),
            500, 502 => new ServerException($message, $statusCode, $body, $e),
            default => new ImmoDataException($message, $statusCode, $body, $e),
        };
    }
}
