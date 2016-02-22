<?php

namespace Core;

/**
 * Class base Controller
 * @package Core
 */
abstract class Controller {

    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Controller constructor.
     * @param $route_params  parameters from the route
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    public function __call($name, $arguments)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false ) {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        } else {
            echo "Method $method not found in controller " . get_class($this);
        }
    }

    /**
     * After filter
     */
    protected function after()
    {

    }

    /**
     * Before filter
     */
    protected function before()
    {

    }
}