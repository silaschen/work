<?php
/**
 * User: silas
 */
namespace easy;

class Routehand {

    static protected $staticRoutes = [];


    static private $methodToRegexToRoutesMap = [];

    const NOT_FOUND = 0;
    const FOUND = 1;
    const METHOD_NOT_ALLOWED = 2;


    static private function parse($route) {
        $regex = '~^(?:/[a-zA-Z0-9_]*|/\{([a-zA-Z0-9_]+?)\})+/?$~';
        if(preg_match($regex, $route, $matches)) {
           
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
    static public function addRoute($httpMethod, $route, $handler) {
        $routeData = self::parse($route);
        // print_r([$routeData,$httpMethod]);die;
        foreach ((array) $httpMethod as $method) {
            if (self::isStaticRoute($routeData)) {
                self::addStaticRoute($httpMethod, $routeData, $handler);
            } else {
                self::addVariableRoute($httpMethod, $routeData, $handler);
            }
        }
    }

    static private function isStaticRoute($routeData) {
        return count($routeData[1]) === 0;
    }

    static private function addStaticRoute($httpMethod, $routeData, $handler) {
        $routeStr = $routeData[0];//route string

        if (isset(self::$staticRoutes[$httpMethod][$routeStr])) {
            throw new \LogicException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $routeStr, $httpMethod
            ));
        }

        if (isset(self::$methodToRegexToRoutesMap[$httpMethod])) {
            foreach (self::$methodToRegexToRoutesMap[$httpMethod] as $route) {
                if ($route->matches($routeStr)) {
                    throw new \LogicException(sprintf(
                        'Static route "%s" is shadowed by previously defined variable route "%s" for method "%s"',
                        $routeStr, $route->regex, $httpMethod
                    ));
                }
            }
        }

        self::$staticRoutes[$httpMethod][$routeStr] = $handler;
        // var_dump(self::$staticRoutes);
    }

    static private function addVariableRoute($httpMethod, $routeData, $handler) {
        list($regex, $variables) = $routeData;

        if (isset(self::$methodToRegexToRoutesMap[$httpMethod][$regex])) {
            throw new \LogicException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $regex, $httpMethod
            ));
        }

        self::$methodToRegexToRoutesMap[$httpMethod][$regex] = new Dispahandler(
            $httpMethod, $handler, $regex, $variables
        );
        // var_dump( self::$methodToRegexToRoutesMap);
    }

    static public function get($route, $handler) {
        self::addRoute('GET', $route, $handler);
    }

    static public function post($route, $handler) {
        self::addRoute('POST', $route, $handler);
    }

    public function put($route, $handler) {
        self::addRoute('PUT', $route, $handler);
    }

    public function delete($route, $handler) {
        self::addRoute('DELETE', $route, $handler);
    }

    public function patch($route, $handler) {
        self::addRoute('PATCH', $route, $handler);
    }

    public function head($route, $handler) {
        self::addRoute('HEAD', $route, $handler);
    }

    /**
     * @param $httpMethod
     * @param $uri
     */
    static public function dispatch($httpMethod, $uri) {
      

        if (array_key_exists($httpMethod, self::$staticRoutes)) {
                
                $staticRoutes = array_keys(self::$staticRoutes[$httpMethod]);
        
                foreach ($staticRoutes as $staticRoute) {
                    if($staticRoute === $uri) {
                        return [self::FOUND, self::$staticRoutes[$httpMethod][$staticRoute], []];
                    }
                }

        }
        
        if (array_key_exists($httpMethod, self::$methodToRegexToRoutesMap)) {
           
                $routeLookup = [];
                $index = 1;
                $regexes = array_keys(self::$methodToRegexToRoutesMap[$httpMethod]);
                foreach ($regexes as $regex) {
                    $routeLookup[$index] = [
                        self::$methodToRegexToRoutesMap[$httpMethod][$regex]->handler,
                        self::$methodToRegexToRoutesMap[$httpMethod][$regex]->variables,
                    ];
                    $index += count(self::$methodToRegexToRoutesMap[$httpMethod][$regex]->variables);
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
}
