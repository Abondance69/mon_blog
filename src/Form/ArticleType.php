<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu', null , [
                'attr' => ['class' => 'textarea']
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'Etat : ',
                'attr' => ['class' => 'choice'],
                'choices' => [
                    'Brouillon' => 'brouillon',
                    'Publié' => 'publie',
                ],
            ])
            // Champ dateAdd caché
            ->add('date_add',  DateTimeType::class, [
                'disabled' => true,
                'label' => false,
                'attr' => [
                    'style' => 'display: none;',
                ],
            ])
            // Champ datePub caché
            ->add('date_pub', DateTimeType::class, [
                'disabled' => true,
                'label' => false,
                'attr' => [
                    'style' => 'display: none;',
                ],
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie : ',
                'attr' => ['class' => 'choice-Id'],
                'class' => Categorie::class,
                'choice_label' => function($categorie){
                    return $categorie->getId() . ' - '. $categorie->getTitre();
                },
            ])
            ->add('auteur', EntityType::class, [
                'label' => 'Auteur : ',
                'attr' => ['class' => 'choice-auteur'],
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getId() . ' - ' . $user->getEmail();
                },
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'submit']
            ]);
    }    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
