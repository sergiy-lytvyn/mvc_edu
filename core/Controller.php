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
}