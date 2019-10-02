<?php

namespace App\Controller;

use App\Quote\DataSource\JsonDataSource;
use App\Quote\QuoteProvider;
use App\Shout\Shouter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    private const MAX_LIMIT = 10;

    /** @var QuoteProvider */
    private $quoteProvider;

    /** @var JsonDataSource */
    private $jsonDataSource;

    /**
     * @param QuoteProvider $quoteProvider
     * @param JsonDataSource $jsonDataSource
     */
    public function __construct(QuoteProvider $quoteProvider, JsonDataSource $jsonDataSource)
    {
        $this->quoteProvider = $quoteProvider;
        $this->jsonDataSource = $jsonDataSource;
    }

    /**
     * @param Request $request
     * @param string $personSlug
     *
     * @return JsonResponse
     */
    public function shout(Request $request, string $personSlug): JsonResponse
    {
        $limit = $request->query->get('limit');

        if (null === $limit) {
            return new JsonResponse(['error' => 'GET parameter [limit] is missing'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($limit)) {
            return new JsonResponse(['error' => '[limit] must be a number'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $limit = (int)$limit;

        if (0 >= $limit || $limit > self::MAX_LIMIT) {
            return new JsonResponse(
                ['error' => '[limit] must be between 1 and ' . self::MAX_LIMIT],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $quotes = $this->quoteProvider->get($personSlug, $limit);

        return new JsonResponse(Shouter::shoutAll($quotes));
    }

    /**
     * @return JsonResponse
     *
     * Endpoint for the sake of the test.
     */
    public function authors(): JsonResponse
    {
        return new JsonResponse($this->jsonDataSource->getAvailable());
    }
}
