<?php
/**
 * User: silas
 */
namespace easy;

class Dispatcher {
    /** @var mixed[][] */
    protected $staticRoutes = [];

    /** @var Route[][] */
    private $methodToRegexToRoutesMap = [];

    const NOT_FOUND = 0;
    const FOUND = 1;
    const METHOD_NOT_ALLOWED = 2;

    /**
     * 提取占位符
     * @param $route
     * @return array
     */
    private function parse($route) {
        $regex = '~^(?:/[a-zA-Z0-9_]*|/\{([a-zA-Z0-9_]+?)\})+/?$~';
        if(preg_match($regex, $route, $matches)) {
            // 区分静态路由和动态路由
            if(count($matches) > 1) {
                preg_match_all('~\{([a-zA-Z0-9_]+?)\}~', $route, $matchesVariables);
                return [
                    preg_replace('~{[a-zA-Z0-9_]+?}~', '([a-zA-Z0-9_]+)', $route),
                    $matchesVariables[1],
                ];
            } else {
                return [
                    $route,
                    [],
                ];
            }
        }
        throw new \LogicException('register route failed, pattern is illegal');
    }

    /**
     * 注册路由
     * @param $httpMethod string | string[]
     * @param $route
     * @param $handler
     */
    public function addRoute($httpMethod, $route, $handler) {
        $routeData = $this->parse($route);
        // print_r([$routeData,$httpMethod]);die;
        foreach ((array) $httpMethod as $method) {
            if ($this->isStaticRoute($routeData)) {
                $this->addStaticRoute($httpMethod, $routeData, $handler);
            } else {
                $this->addVariableRoute($httpMethod, $routeData, $handler);
            }
        }
    }

    private function isStaticRoute($routeData) {
        return count($routeData[1]) === 0;
    }

    private function addStaticRoute($httpMethod, $routeData, $handler) {
        $routeStr = $routeData[0];//route string

        if (isset($this->staticRoutes[$httpMethod][$routeStr])) {
            throw new \LogicException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $routeStr, $httpMethod
            ));
        }

        if (isset($this->methodToRegexToRoutesMap[$httpMethod])) {
            foreach ($this->methodToRegexToRoutesMap[$httpMethod] as $route) {
                if ($route->matches($routeStr)) {
                    throw new \LogicException(sprintf(
                        'Static route "%s" is shadowed by previously defined variable route "%s" for method "%s"',
                        $routeStr, $route->regex, $httpMethod
                    ));
                }
            }
        }

        $this->staticRoutes[$httpMethod][$routeStr] = $handler;
        // var_dump($this->staticRoutes);
    }

    private function addVariableRoute($httpMethod, $routeData, $handler) {
        list($regex, $variables) = $routeData;

        if (isset($this->methodToRegexToRoutesMap[$httpMethod][$regex])) {
            throw new \LogicException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $regex, $httpMethod
            ));
        }

        $this->methodToRegexToRoutesMap[$httpMethod][$regex] = new Route(
            $httpMethod, $handler, $regex, $variables
        );
    }

    public function get($route, $handler) {
        $this->addRoute('GET', $route, $handler);
    }

    public function post($route, $handler) {
        $this->addRoute('POST', $route, $handler);
    }

    public function put($route, $handler) {
        $this->addRoute('PUT', $route, $handler);
    }

    public function delete($route, $handler) {
        $this->addRoute('DELETE', $route, $handler);
    }

    public function patch($route, $handler) {
        $this->addRoute('PATCH', $route, $handler);
    }

    public function head($route, $handler) {
        $this->addRoute('HEAD', $route, $handler);
    }

    /**
     * 分发
     * @param $httpMethod
     * @param $uri
     */
    public function dispatch($httpMethod, $uri) {
        $staticRoutes = array_keys($this->staticRoutes[$httpMethod]);
        
        foreach ($staticRoutes as $staticRoute) {
            if($staticRoute === $uri) {
                return [self::FOUND, $this->staticRoutes[$httpMethod][$staticRoute], []];
            }
        }

        $routeLookup = [];
        $index = 1;
        $regexes = array_keys($this->methodToRegexToRoutesMap[$httpMethod]);
        foreach ($regexes as $regex) {
            $routeLookup[$index] = [
                $this->methodToRegexToRoutesMap[$httpMethod][$regex]->handler,
                $this->methodToRegexToRoutesMap[$httpMethod][$regex]->variables,
            ];
            $index += count($this->methodToRegexToRoutesMap[$httpMethod][$regex]->variables);
        }
        $regexCombined = '~^(?:' . implode('|', $regexes) . ')$~';
        if(!preg_match($regexCombined, $uri, $matches)) {
            return [self::NOT_FOUND];
        }
        for ($i = 1; '' === $matches[$i]; ++$i);
        list($handler, $varNames) = $routeLookup[$i];
        $vars = [];
        foreach ($varNames as $varName) {
            $vars[$varName] = $matches[$i++];
        }
        return [self::FOUND, $handler, $vars];
    }
}
