<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez remplir ce champ'
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Le titre doit contenir au moins 2 caractères.'
                ])
                ],
            'attr' => ['placeholder' => 'Titre de l\'annonce'],
        ])
        ->add('description', TextareaType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez remplir ce champ'
                ]),
                new Length([
                    'min' => 5,
                    'minMessage' => 'La description doit contenir au moins 2 caractères.'
                ])
                ],
            'attr' => ['placeholder' => 'Description de l\'annonce'],
        ])
        ->add('price', IntegerType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez remplir ce champ.'
                ]),
                ],
            'attr' => ['placeholder' => 'Prix'],
        ])
        
        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'title'])
           
       
        ->add('submit', SubmitType::class,[
            'attr' => ['class' => 'btn btn-success '],
            'label' => 'Poster'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
