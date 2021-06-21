<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Picture', FileType::class,[
                'mapped' => false,
                'required' => false,
                'attr' =>[
                    'accept' => 'image/*'
                ]
            ])
            ->add('Bio', TextareaType::class,[
                'label' => "Describe Your Self"
            ])
            ->add('email', EmailType::class,[
                'label' => "E-mail Address"
            ])
            ->add('Name', TextType::class,[
                'label' => "First Name "
            ])
            ->add('Prename', TextType::class,[
                'label' => "Last Name "
            ])
            
            ->add('Address', TextareaType::class,[
                'label' => "Address "
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
