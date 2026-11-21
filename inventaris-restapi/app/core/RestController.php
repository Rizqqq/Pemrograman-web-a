<?php
abstract class RestController
{
    protected ApiRequest $request;
    protected array $context = [];
    protected int $userId = 0;

    public function __construct()
    {
        $this->request = ApiRequest::instance();
    }

    protected function boot(): void
    {
        $this->context = ApiAuth::authenticate();
        $this->userId = (int) ($this->context['user']['id'] ?? 0);

        $this->context['rate_limit'] = ApiRateLimiter::enforce(
            'token:' . $this->context['token_id'],
            API_RATE_LIMIT,
            API_RATE_WINDOW
        );
    }

    protected function requirePermission(string $permission): void
    {
        if (!ApiAuth::can($permission)) {
            ApiResponse::forbidden('Token ini tidak memiliki izin "' . $permission . '".');
        }
    }

    protected function validate(array $rules, ?array $data = null): array
    {
        $validator = (new ApiValidator($data ?? $this->request->json()))->labels([]);
        $validated = $validator->validate($rules);

        if ($validator->fails()) {
            ApiResponse::validation($validator->errors());
        }

        return $validated;
    }
}