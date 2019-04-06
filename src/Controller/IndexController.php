<?php

namespace App\Controller;

use App\Repository\PasswordKeyRepositoryInterface;
use App\Security\AuthUser;
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
    public function index(PasswordKeyRepositoryInterface $passwordKeyRepository): Response
    {
        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();

        $ownPasswords = $passwordKeyRepository->findAllByOwnerGroupedByPassword($user);
        $sharedPasswords = $passwordKeyRepository->findSharedForUser($user);

        return $this->render('index.html.twig', [
            'ownPasswords' => $ownPasswords,
            'sharedPasswords' => $sharedPasswords,
        ]);
    }

}