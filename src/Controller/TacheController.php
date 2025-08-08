<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Statut;
use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class  TacheController extends AbstractController
{
    #[Route('/projet/{projet}/statut/{statut}/tache/add', name: 'app_tache_add')]
    public function add(Request $request, Projet $projet, Statut $statut, EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setProjet($projet);
        $tache->setStatut($statut);

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tache);
            $em->flush();

            return $this->redirectToRoute('app_projet_show', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('tache/add.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/tache/{id}/edit', name: 'app_tache_edit')]
    public function edit(Request $request, Tache $tache, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_projet_show', [
                'id' => $tache->getProjet()->getId(),
            ]);
        }

        return $this->render('tache/edit.html.twig', [
            'form' => $form->createView(),
            'tache' => $tache,
        ]);
    }

    #[Route('/tache/{id}/delete', name: 'app_tache_delete')]
    public function delete(Tache $tache, EntityManagerInterface $em): Response
    {
        $projet = $tache->getProjet();

        $em->remove($tache);
        $em->flush();

        return $this->redirectToRoute('app_projet_show', [
            'id' => $projet->getId(),
        ]);
    }
}
