<?php
namespace Core;

class Router
{

    /**
     * Associative array of routers (the routing table)
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from matched route
     * @var array
     */
    protected $params = [];

    /**
     * Add routing to the routing table
     *
     * @param string $route The route URL
     * @param array $params Parameters (controller, action)
     * @return void
     */
    public function add($route, $params = [])
    {
        //Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        //Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        //Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P</1>\2', $route);

        //Add start and end delimeters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if route is found
     *
     * @param string $url
     * @return bool true if match found, false otherwise
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $params = [];
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }


    /**
     * Get the currently matched parameters
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            //$controller = "App\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    echo "Method $action (in controller $controller) not found";
                }
            } else {
                echo "Controller class $controller not found";
            }
        } else {
            echo "No route matched";
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps
     * post-authors => PostAuthors
     *
     * @param $string
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to CamelCase
     * add-new => addNew
     *
     * @param $string - the string to convert
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any). As the full query string is used for the route,
     * any variables at the end will need to be removed before the route is matched to the routing table.
     * Example:
     *
     * URL:                         $_SERVER[QUERY_STRING]      Route
     * -----------------------------------------------------------------------
     * localhost                    ''                          ''
     * localhost/?                  ''                          ''
     * localhost/?page=1            page=1                      ''
     * localhost/posts?page=1       posts&page=1                posts
     * localhost/index              posts/index                 posts/index
     * localhost/index?page=1       posts/index&page=1          posts/index
     *
     *
     * A URL of the format localhost/?page (one viarable name, no value_ wont`t work however.
     * (The .htaccess file converts the first ? to a & when it`s passed throught to the $_SERVER variable)
     *
     * @param string $url the full url
     * @return string the url with the query string variables removed
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined int the route
     * parameters is added if present
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .=  $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}