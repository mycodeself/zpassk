<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route(path="/profile", name="profile")
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request): Response
    {
        return $this->render('profile/profile.html.twig');
    }
}