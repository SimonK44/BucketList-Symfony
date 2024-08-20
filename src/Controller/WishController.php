<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'app_liste')]
    public function liste(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();

        return $this->render('main/wish-list.html.twig', [
            'wishes' => $wishes]);
    }

    #[Route('/wish/{id}', name: 'app_detail', requirements: ['id' => '\d+'])]
    public function detail(WishRepository $wishRepository, int $id): Response
    {
        $wish = $wishRepository->find($id);

        return $this->render('main/wish-detail.html.twig', [
            'wish' => $wish
        ]);
    }

    #[Route('/wishes/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();

        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('app_liste');
        }

        return $this->render('main/wish-create.html.twig', [
            'wish_form' => $form
            ]);

    }

}
