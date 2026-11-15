<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Projet;
use App\Entity\Statut;
use App\Entity\Tache;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la tâche',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date',
            ])
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'nom',
                'label' => 'Statut',
                'choices' => $options['projet']->getStatuts()->toArray(),
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'required' => false,
                'choice_label' => fn(Employe $e) => $e->getPrenom() . ' ' . $e->getNom(),
                'label' => 'Membre',
                'placeholder' => '',
                'choices' => $options['projet']->getEmployes()->toArray(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
        $resolver->setRequired('projet');
        $resolver->setAllowedTypes('projet', Projet::class);
    }
}
