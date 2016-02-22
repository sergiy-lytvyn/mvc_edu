<?php

class Posts{

    /**
     * Show the index page
     *
     * @return void
     */
    public function index()
    {
        echo "Hello form the index action in the Posts controller";
    }

    /**
     * Show the add new page
     *
     * @return void
     */
    public function addNew()
    {
        echo "Hello form the show action in the Posts controller";
    }
}