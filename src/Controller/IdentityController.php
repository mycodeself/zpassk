<?php

namespace App\Controller;

use App\Form\Type\IdentityType;
use App\Service\DTO\IdentityDTO;
use App\Service\IdentityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class IdentityController extends AbstractController
{

    /**
     * @Route(path="/groups/{groupId}/identities/create", name="identity_create")
     *
     * @param Request $request
     * @param IdentityService $identityService
     * @return Response
     */
    public function create(Request $request, string $groupId, IdentityService $identityService): Response
    {
        if(!$identityService->loggedInUserHasPermissionInGroupId($groupId)) {
            throw new AccessDeniedHttpException();
        }

        $identityDTO = new IdentityDTO();
        $identityDTO->setGroupId($groupId);
        $form = $this->createForm(IdentityType::class, $identityDTO);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $identityService->create($data);
            $this->addFlash('success', 'The identity has been created');
            return new RedirectResponse($this->generateUrl('index'));
        }

        return $this->render('identity/identity_create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}