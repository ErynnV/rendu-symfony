<?php

namespace App\Form;

use App\Entity\Utensil;
use App\Repository\UtensilRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UtensilSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utensil', EntityType::class, [
                'class' => Utensil::class,
                'choice_label' => 'name',
                'query_builder' => function (UtensilRepository $ur): QueryBuilder {
                    return $ur->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Select'
            ])
        ;
    }
}