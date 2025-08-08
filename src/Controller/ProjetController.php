<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Entity\Statut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'app_projet')]
    public function index(ProjetRepository $projetRepository): Response
    {
        $projets = $projetRepository->findNonArchives();
        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }


    #[Route('/projet/{id<\d+>}', name: 'app_projet_show')]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }


    #[Route('/projet/{id}/edit', name: 'app_projet_edit')]
    public function edit(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_projet_show', ['id' => $projet->getId()]);
        }

        return $this->render('projet/edit.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }


    #[Route('/projet/add', name: 'app_projet_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setArchive(false);

            // ✅ Crée les statuts automatiquement
            $statuts = ['To Do', 'Doing', 'Done'];
            foreach ($statuts as $nomStatut) {
                $statut = new Statut();
                $statut->setNom($nomStatut);
                $statut->setProjet($projet);
                $projet->addStatut($statut);
                $em->persist($statut);
            }

            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projet/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/projet/{id}/archive', name: 'app_projet_archive')]
    public function archive(Projet $projet, EntityManagerInterface $em): Response
    {
        $projet->setArchive(true);
        $em->flush();

        return $this->redirectToRoute('app_home'); // ou autre route selon ton besoin
    }

}
