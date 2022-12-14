<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Oeuvres;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Oeuvres2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('titre', TextType::class, [
            "label" => "Titre du produit*",
            "required" => false,
            "help" => "Saisir un titre entre .. et .. caractères",
            "attr" => [ 
                "placeholder" => "Saisir un titre",
                "class" => "border border-danger bg-light",
              
            ],
            "label_attr" => [ 
                "class" => "text-primary"
            ],
            "row_attr" => [
                "id" => "titreBlock"
            ],
          
        ])
            ->add('prix')
            ->add('stock')
            // ->add('DateAt')
            ->add('categorie', EntityType::class, [ 
                "class" => Categorie::class, 
                 "choice_label" => "nom", 
                 "placeholder" => "Sélectionner une catégorie",
                 "required" => false,
                 "label" => "Catégorie*",
                //  "multiple" => true,
                 //"expanded" => true, // radio/checkbox
                 
            ])

            ->add('description', TextareaType::class, [
                "help" => "Description facultative",
                "required" => false,
                "attr" => [
                    "rows" => 8,
                    "class" => "border border-info bg-light",
                    "style"=>"margin:1rem"
                ],
                "label_attr" => [ 
                    "class" => "text-danger"
                ]
            ])
            ->add("matieres", EntityType::class, [
                "class" => Matiere::class,
                "choice_label" => "nom",
                "required" => false,
                "placeholder" => "Sélectionnez",
                "multiple" => true, 
            //    "expanded" => true, 
               "label" => "Matière(s)*",
               "attr" => [
                   "class" => "col-12"
               ]
            ])
            ->add('dimention',TextareaType::class,[
                "help"=>"facultatif",
                // "placeholder"=>"dimention en centimetre",
                "attr" => [
                    "rows" => 8,
                    "class" => "border border-info bg-light",
                    "style"=>"margin:1rem"
                ]
            ])

            

        ;
        
        if($options['ajouter'])
        {
        $builder->add('image',FileType::class,[
            "required"=>false,
            "data_class" => null,
            "attr"=>[
                'onchange'=>"loadFile(event)"
            ]
        ] )
           ;
            }

         if($options['modifier'])
         {
             $builder->add('imageUpdate', FileType::class, [
                 "required" => false,
                 "mapped" => false, // qui n'est pas dans l'entity
                "data_class" => null,
                 "attr" => [
                     'onchange' => "loadFile(event)"
                 ]
             ]);
         } 
    }  
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvres::class,
            'ajouter'=>false,
            'modifier'=>false,
        ]);
    }
}
