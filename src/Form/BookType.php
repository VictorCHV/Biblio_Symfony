<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\PublishingHouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du livre',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' =>'Description du livre',
                'required' => false
            ])
            ->add('imageUrl', UrlType::class, [
                'label' => 'image du livre',
                'required' => false,
            ]) 
            ->add('author', EntityType::class, [
                'label' => 'Choix de l\'auteur',
                'required' => false,
                //spécifie l'entité qu'on veut pouvoir sélectionner
                'class' => Author::class,
                //spécifie la propriété de la classe Author qu'on veut afficher ici : author.name
                'choice_label' => 'name',

            ])
            ->add('categories', EntityType::class, [
                'label' => 'Choix des catégories',
                'required' => false,
                //spécifie l'entité qu'on veut pouvoir sélectionner
                'class' => Category::class,
                //spécifie la propriété de la classe Category qu'on veut afficher ici : category.name
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('publishinghouses', EntityType::class, [
                'label' => 'Choix de la maison d\'edition',
                'required' => false,
                //spécifie l'entité qu'on veut pouvoir sélectionner
                'class' => PublishingHouse::class,
                //spécifie la propriété de la classe PublishingHouse qu'on veut afficher ici : publishinghouse.name
                'choice_label' => 'name',
            ])

            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
