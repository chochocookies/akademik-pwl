<?php
class Router {
    private array $routes = [];
    private string $prefix = '';

    public function get(string $path, callable|array $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void {
        $this->addRoute('POST', $path, $handler, 'PUT');
    }

    public function delete(string $path, callable|array $handler): void {
        $this->addRoute('POST', $path, $handler, 'DELETE');
    }

    public function group(string $prefix, callable $callback): void {
        $previousPrefix = $this->prefix;
        $this->prefix .= $prefix;
        $callback($this);
        $this->prefix = $previousPrefix;
    }

    private function addRoute(string $method, string $path, callable|array $handler, ?string $spoofMethod = null): void {
        $this->routes[] = [
            'method'      => $method,
            'spoof'       => $spoofMethod,
            'path'        => $this->prefix . $path,
            'handler'     => $handler,
            'pattern'     => $this->buildPattern($this->prefix . $path),
        ];
    }

    private function buildPattern(string $path): string {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $spoofed = strtoupper($_POST['_method'] ?? '');

        // Remove base path prefix
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = parse_url(APP_URL, PHP_URL_PATH);
        $uri = '/' . ltrim(substr($uri, strlen($basePath)), '/');
        $uri = $uri === '' ? '/' : $uri;

        foreach ($this->routes as $route) {
            $matchMethod = $route['method'];
            $matchSpoof  = $route['spoof'];

            $methodMatch = ($method === $matchMethod) && 
                           ($matchSpoof === null || $spoofed === $matchSpoof);

            if (!$methodMatch) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        view('errors.404');
    }

    private function callHandler(callable|array $handler, array $params = []): void {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            if (!class_exists($controllerClass)) {
                die("Controller $controllerClass not found.");
            }
            $controller = new $controllerClass();
            call_user_func_array([$controller, $method], $params);
        }
    }
}
