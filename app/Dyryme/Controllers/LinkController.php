<?php namespace Dyryme\Controllers;

/**
 * Link controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */

class LinkController
{
    public function index()
    {
        return \View::make('home');
    }


    public function create()
    {
        return \View::make('create');
    }


    public function store()
    {
        // TODO: implement store method
    }


}
