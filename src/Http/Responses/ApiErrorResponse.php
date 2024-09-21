<?php

namespace ShamiqThedev\LaravelCrudGen\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use ShamiqThedev\LaravelCrudGen\Http\Responses\ApiResponse;

class ApiErrorResponse extends ApiResponse
{
    public function __construct(
        protected string $message = 'An error occurred. Please try again later.',
        protected ?array $errors = [],
        int $status = Response::HTTP_BAD_REQUEST,
        array $headers = []
    ) { parent::__construct([], $status, $headers); }

    public function toResponse($request): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $this->message ?? null,
        ];

        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return response()->json($response, $this->status, $this->headers);
    }
}
