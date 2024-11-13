<?php

namespace App\Factories;

use App\Trait\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ErrorFactory
{
    use ApiResponse;

    /**
     * The exceptions that we want to have custom handling.
     *
     * @var array<string, string>
     */
    public array $handlers = [
        ValidationException::class => 'handleValidationException',
    ];

    public function __construct(
        public \Throwable $exception,
        public Request    $request
    )
    {
    }

    public function handle(): JsonResponse
    {
        $className = get_class($this->exception);
        $classShortName = (new \ReflectionClass($this->exception))->getShortName();

        // Check our handler prop to see if we defined any custom handler for this exception type
        if (array_key_exists($className, $this->handlers)) {
            $method = $this->handlers[$className];
            return $this->$method($this->exception);
        }

        // Check exception code to see if its valid HTTP response code
        $statusCode = array_key_exists($this->exception->getCode(), Response::$statusTexts)
            ? $this->exception->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        return $this->respondError([
            'message' => $this->exception->getMessage(),
            'type' => $classShortName,
        ], $statusCode);
    }

    private function handleValidationException(ValidationException $exception): JsonResponse
    {
        $errors = [];

        foreach ($exception->errors() as $key => $value)
            foreach ($value as $message) {
                $errors[] = [
                    'message' => $message,
                    'field' => $key,
                ];
            }

        return self::respondError($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
