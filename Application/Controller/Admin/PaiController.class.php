<?php


namespace Application\Controller\Admin;


use Framework\Controller;

class PaiController extends Controller
{
    public function index()
    {
        $this->display('pai');
    }
}