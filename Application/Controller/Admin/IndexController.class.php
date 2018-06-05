<?php


namespace Application\Controller\Admin;


use Framework\Controller;

class IndexController extends PlatformController
{
	public function index()
    {
        $this->display('index');
    }
}