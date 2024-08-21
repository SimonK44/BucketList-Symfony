<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\SerieType;
use App\Form\WishType;
use App\Repository\SerieRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'app_liste')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);

        return $this->render('wish/wish-list.html.twig', [
            'wishes' => $wishes]);
    }

    #[Route('/wish/{id}', name: 'app_detail', requirements: ['id' => '\d+'])]
    public function detail(WishRepository $wishRepository, int $id): Response
    {
        $wish = $wishRepository->find($id);

        if(!$wish) {
            throw $this->createNotFoundException('Wish not found');
        }

        return $this->render('wish/wish-detail.html.twig', [
            'wish' => $wish
        ]);
    }

    #[Route('/wishes/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();

        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $wish->setPublished(true);

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('app_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/wish-create.html.twig', [
            'wish_form' => $form
            ]);

    }

    #[Route('/update/{id}', name: 'app_update', requirements: ['id' => '\d+'])]
    public function update(Request $request,
                           EntityManagerInterface $em,
                           WishRepository $wishRepository,
                           int $id): Response
    {
        $wish = $wishRepository->find($id);

        if(!$wish){
            throw $this->createNotFoundException('Wish not found');
        }

        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wish->setDateUpdated(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Un wish a été modifié');

            return $this->redirectToRoute('app_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/wish-create.html.twig', [
            'wish_form' => $form
        ]);
    }

    #[Route('/wishes/{id}/delete', name: 'app_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Request $request,
                           EntityManagerInterface $em,
                           WishRepository $wishRepository,
                           int $id): Response
    {
        $wish = $wishRepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('Wish not found');
        }
        if($this->isCsrfTokenValid('delete'.$id, $request->query->get('token'),)){
            $em->remove($wish, true);
            $this->addFlash('success', 'Wish successfully deleted!');
        } else {
            $this->addFlash('danger', 'This wish can not be deleted!');
        }
        return $this->redirectToRoute('app_liste');
    }



}
