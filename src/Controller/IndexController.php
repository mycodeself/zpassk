<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route(path="/", name="index")
     *
     * @return Response
     */
    public function index(): Response
    {
        $groups = [];

        return $this->render('index.html.twig', [
            'groups' => $groups
        ]);
    }

}