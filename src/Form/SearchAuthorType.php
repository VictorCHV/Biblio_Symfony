<?php

namespace App\Form;

use App\DTO\SearchAuthorCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom auteur : ',
                'required' => false
            ])
            ->add('orderBy', ChoiceType::class, [
                'choices' => [
                    'Identifiant' => 'id',
                    'Nom' => 'name'
                ],
                'required' => true,
                'label' => 'Trier par :'
            ])
            ->add('direction', ChoiceType::class, [
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
                'required' => true,
                'label' => 'Sens du tri :'
            ])
            ->add('limit', NumberType::class, [
                'required' => true,
                'label' => 'Nombre de Résultats : '
            ])
            ->add('page', NumberType::class, [
                'required' => true,
                'label' => 'Page : '

            ])

            ->add('send', SubmitType::class, [
                'label' => "Envoyer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchAuthorCriteria::class,
            'method' => 'GET', 
            'csrf_protection' => false
        ]);
    }
}
