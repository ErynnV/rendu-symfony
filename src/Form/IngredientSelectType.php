<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'query_builder' => function (IngredientRepository $ir): QueryBuilder {
                    return $ir->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Select'
            ])
        ;
    }
}