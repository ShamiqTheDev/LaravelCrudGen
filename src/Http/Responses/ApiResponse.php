<?php

namespace ShamiqThedev\LaravelCrudGen\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

abstract class ApiResponse implements Responsable
{
    public function __construct(
        protected mixed $data = null,
        protected int $status = Response::HTTP_OK,
        protected array $headers = []
    ) {}

    abstract public function toResponse($request);
}
