<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Picture', FileType::class,[
                'mapped' => false,
                'required' => false,
                'attr' =>[
                    'accept' => 'image/*',                    
                ]
            ])
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => "The Mission Specification"
            ])
            ->add('title', TextType::class, [
                'label' => "Title"
            ])
            ->add('Description', TextareaType::class, [
                'label' => "Describe Your Mission here !!"
            ])
            ->add('Cost', NumberType::class, [
                'label' => 'Your Cost offer'
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
