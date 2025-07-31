<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Projet;
use App\Entity\Statut;
use App\Entity\Tache;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des employés
        $employes = [];
        for ($i = 1; $i <= 5; $i++) {
            $employe = new Employe();
            $employe->setPrenom("Prenom$i");
            $employe->setNom("Nom$i");
            $employe->setEmail("user$i@example.com");
            $employe->setStatut("CDI");
            $employe->setDateEntree(new \DateTime('-'.mt_rand(1, 5).' years'));
            $manager->persist($employe);
            $employes[] = $employe;
        }

        // Créer un projet
        $projet = new Projet();
        $projet->setNom('Projet Alpha');
        $projet->setArchive(false);
        foreach ($employes as $employe) {
            $projet->addEmploye($employe);
        }
        $manager->persist($projet);

        // Créer des statuts
        $statuts = [];
        foreach (['To Do', 'Doing', 'Done'] as $nomStatut) {
            $statut = new Statut();
            $statut->setNom($nomStatut);
            $statut->setProjet($projet);
            $manager->persist($statut);
            $statuts[] = $statut;
        }

        // Créer des tâches
        for ($j = 1; $j <= 10; $j++) {
            $tache = new Tache();
            $tache->setTitre("Tâche $j");
            $tache->setDescription("Description de la tâche $j");
            $tache->setDeadline(new \DateTime("+$j days"));
            $tache->setProjet($projet);
            $tache->setStatut($statuts[array_rand($statuts)]);
            $tache->setEmploye($employes[array_rand($employes)]);
            $manager->persist($tache);
        }

        $manager->flush();
    }
}
