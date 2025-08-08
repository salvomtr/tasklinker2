<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Titre du projet',
            ])
            ->add('employes', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $e) {
                    return $e->getPrenom() . ' ' . $e->getNom();
                },
                'multiple' => true,
                'expanded' => false,
                'label' => 'Inviter des membres',
                'attr' => ['class' => 'select2'], // pour activer Select2 via JS
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }

}
