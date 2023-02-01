<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use App\DTO\SearchBookCriteria;
use App\Entity\PublishingHouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de livre : ',
                'required' => false
            ])
            ->add('authors', EntityType::class,[
                'label' => 'Auteurs : ',
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('categories', EntityType::class,[
                'label' => 'Categories: : ',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false
                ])
            ->add('minPrice', MoneyType::class,[
                'label' => 'Prix Minimum',
                'required' => false
            ])
            ->add('maxPrice', MoneyType::class,[
                'label' => 'Prix Maximum',
                'required' => false
            ])
            ->add('publishingHouses', EntityType::class,[
                'label' => 'Maisons d\'édition : ',
                'class' => PublishingHouse::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('orderBy', ChoiceType::class,[
                'choices'  => [
                    'Identifiant' => 'id',
                    'Titre' => 'title', 
                    'Prix' => 'price', 
                 ],
                 'required' => true,
                 'label' => 'Trier par: ',
            ])
            ->add('direction', ChoiceType::class,[
                'choices'  => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',  
                 ],
                 'required' => true,
                 'label' => 'Sens du tri : ',
            ])
            ->add('limit', NumberType::class,[
                'required' => true,
                'label' => 'Nombre de résultats : ',
            ] )
            ->add('page',  NumberType::class,[
                'required' => true,
                'label' => 'Page : ',
            ] )
            
            ->add('send', SubmitType::class, [
                'label' => "Envoyer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchBookCriteria::class,
            'method' => 'GET', 
            'csrf_protection' => false
        ]);
    }
}
