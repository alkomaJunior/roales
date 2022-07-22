<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\AttractionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param AttractionService $attractionService
     *
     * @return Response
     */
    #[Route('/user-favorite', name: 'app_user_favorite')]
    public function favorite(AttractionService $attractionService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        $favorites = $user->getAttractions();

        return $this->render('user/favorite.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/user-edit', name: 'app_user_edit')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        $editForm = $this->createForm(RegistrationFormType::class, $user, [
            'validation_groups' => ['register']
        ]);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $user->setFullName($user->getFullName());
            $user->getEmail($user->getEmail());

            $entityManager->flush();

            $this->addFlash('success', 'Profile successfully updated!');

            return $this->redirectToRoute('app_user_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/index.html.twig', [
            'registrationForm' => $editForm,
        ]);
    }
}
