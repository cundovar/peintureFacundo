<?php

namespace App\Form;

use App\Entity\Categorie;
use App\filter\OeuvreFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OeuvreFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //     ->add('field_name')
        // ;
        ->add("recherche",TextType::class,[
            "required"=>false,
            "label"=>false,
            "attr" =>
             ["placeholder"=>"recherche"]
        ]  )

        // ->add("min",MoneyType::class,[
        //     "required"=>false,
        //     "label"=>false,
        //     "attr"=>
        //     ["placeholder"=>"prix minimum"]

        // ] )

        // ->add("max",MoneyType::class,[
        //     "required"=>false,
        //     "label"=>false,
        //    "attr"=>
        //    [ "placeholder"=>"prix maximun"]

        // ] )


        // -> add("order",ChoiceType::class,[
        //     "required"=>false,
        //     "label"=>false,
        //     "choices"=>[
        //         "prix croissant"=>1,
        //         "prix decroissant"=>2,
        //         "prix croissant"=>3,
        //         "prix décroissant"=>4
        //     ]
        // ] )


        // ->add("Categories", EntityType::class,[
        //     "class"=>Categorie::class,
        //     "choice_label"=>"nom",
        //     "multiple"=>true,
        //     "expanded"=>true,
        //     "required"=>false
        // ] )

    ;
}

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            "data_class"=>OeuvreFilter::class
        ]);
    }
}