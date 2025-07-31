<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'app_projet')]
    public function index(): Response
    {
        return $this->render('projet/index.html.twig', [
            'controller_name' => 'ProjetController',
        ]);
    }

    //#[Route('/projet/{id}', name: 'app_projet_show')]
    #[Route('/projet/{id<\d+>}', name: 'app_projet_show')]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{id}/edit', name: 'app_projet_edit')]
    public function edit(Projet $projet): Response
    {
        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/add', name: 'app_projet_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projet/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
