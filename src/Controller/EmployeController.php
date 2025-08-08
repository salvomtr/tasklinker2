<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employes')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'app_employe_index')]
    public function index(EmployeRepository $employeRepository): Response
    {
        return $this->render('employe/index.html.twig', [
            'employes' => $employeRepository->findAll(),
        ]);
    }

    #[Route('/employe/{id}/edit', name: 'app_employe_edit')]
    public function edit(Request $request, Employe $employe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_employe_index');
        }

        return $this->render('employe/edit.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }



    #[Route('/employe/{id}/delete', name: 'app_employe_delete', methods: ['POST'])]
    public function delete(Employe $employe, EntityManagerInterface $em): Response
    {
        // Supprimer employé des projets
        foreach ($employe->getProjets() as $projet) {
            $employe->removeProjet($projet); // Relation ManyToMany
        }

        // Dissocier l’employé de ses tâches
        foreach ($employe->getTaches() as $tache) {
            $tache->setEmploye(null);
        }

        $em->remove($employe);
        $em->flush();

        $this->addFlash('success', 'Employé supprimé.');

        return $this->redirectToRoute('app_employe_index');
    }

}
