<?php

namespace ShamiqThedev\LaravelCrudGen\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use ShamiqThedev\LaravelCrudGen\Http\Responses\ApiResponse;

class ApiSuccessResponse extends ApiResponse
{
    public function __construct(
        protected mixed $data = null,
        protected ?string $message = 'Operation successful!',
        int $status = Response::HTTP_OK,
        array $headers = []
    ) {
        parent::__construct($data, $status, $headers);
    }

    public function toResponse($request): JsonResponse
    {

        $response = [
            'success' => true,
            'message' => $this->message,
        ];

        // if ($this->data !== null) {
        $response['data'] = $this->data;
        // }

        return response()->json($response, $this->status, $this->headers);
    }
}
