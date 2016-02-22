<?php

namespace App\Controllers;

use Core\Controller;

class Post extends Controller{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        echo "Hello form the index action in the Posts controller";
    }

    /**
     * Show the add new page
     *
     * @return void
     */
    public function addNewAction()
    {
        echo "Hello form the show action in the Posts controller";
    }

    public function editAction()
    {
        echo 'Edit action';
        var_dump($this->route_params);
    }

    
}