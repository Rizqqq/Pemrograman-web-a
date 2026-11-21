<?php
final class ApiRouter
{
    private array $routes = [];

    public function add(string $method, string $pattern, $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => '/' . trim($pattern, '/'),
            'handler' => $handler,
        ];
    }

    public function get(string $pattern, $handler): void    { $this->add('GET', $pattern, $handler); }
    public function post(string $pattern, $handler): void   { $this->add('POST', $pattern, $handler); }
    public function put(string $pattern, $handler): void    { $this->add('PUT', $pattern, $handler); }
    public function patch(string $pattern, $handler): void  { $this->add('PATCH', $pattern, $handler); }
    public function delete(string $pattern, $handler): void { $this->add('DELETE', $pattern, $handler); }

    public function match(string $method, string $path): ?array
    {
        $method = strtoupper($method);
        $lookup = $method === 'HEAD' ? 'GET' : $method;
        $path = '/' . trim($path, '/');

        foreach ($this->routes as $route) {
            $params = $this->matchPattern($route['pattern'], $path);
            if ($params !== null && $route['method'] === $lookup) {
                return [
                    'method'  => $route['method'],
                    'handler' => $route['handler'],
                    'params'  => $params,
                ];
            }
        }

        return null;
    }

    public function allowedMethods(string $path): array
    {
        $path = '/' . trim($path, '/');
        $allowed = [];

        foreach ($this->routes as $route) {
            if ($this->matchPattern($route['pattern'], $path) !== null) {
                $allowed[$route['method']] = true;
            }
        }

        if (isset($allowed['GET'])) {
            $allowed['HEAD'] = true;
        }
        $allowed['OPTIONS'] = true;

        return array_keys($allowed);
    }

    public function dispatch(string $method, string $path): void
    {
        $match = $this->match($method, $path);

        if ($match !== null) {
            $handler = $match['handler'];

            if (is_array($handler) && is_string($handler[0])) {
                $class = $handler[0];
                $action = $handler[1];
                $controller = new $class();
                $controller->{$action}($match['params']);
                return;
            }

            if (is_callable($handler)) {
                call_user_func($handler, $match['params']);
                return;
            }

            ApiResponse::error(500, 'Handler route tidak valid.');
        }

        $allowed = $this->allowedMethods($path);
        if ($allowed !== ['OPTIONS']) {
            ApiResponse::methodNotAllowed(implode(', ', $allowed));
        }

        ApiResponse::notFound('Endpoint API tidak ditemukan.');
    }

    private function matchPattern(string $pattern, string $path): ?array
    {
        if ($pattern === $path) {
            return [];
        }

        $patternSegments = explode('/', trim($pattern, '/'));
        $pathSegments = explode('/', trim($path, '/'));

        if (count($patternSegments) !== count($pathSegments)) {
            return null;
        }

        $params = [];
        foreach ($patternSegments as $index => $segment) {
            $value = $pathSegments[$index];

            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $segment, $matches)) {
                $params[$matches[1]] = $value;
                continue;
            }

            if ($segment !== $value) {
                return null;
            }
        }

        return $params;
    }
}