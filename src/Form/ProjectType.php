<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Employee;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du projet',
            ])
            ->add('members', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Employés',
                'attr' => ['class' => 'search-and-select'],
            ])
            ->add('allowedStatuses', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Statuts autorisés',
                'attr' => ['class' => 'search-and-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}