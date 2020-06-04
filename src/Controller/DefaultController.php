<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        $response = new Response();
        $response->setContent('Avia API v1');
        return $response;
    }
}