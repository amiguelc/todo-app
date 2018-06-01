<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Users;

class UsersRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class)
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ))
        ->add('nickname', TextType::class)
        ->add('name', TextType::class)
        ->add('locale', ChoiceType::class, array(
            'choices' => array(
                'English' => 'en',
                'Spanish' => 'es_ES',
            )
        ))            
        ->add('save', SubmitType::class, array('label' => 'Register'));
        
        /*
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('nickname', TextType::class)
            ->add('name', TextType::class)
            ->add('locale', ChoiceType::class, array(
                'choices' => array(
                    'English' => 'en',
                    'Spanish' => 'es_ES',
                )
            ))            
            ->add('save', SubmitType::class, array('label' => 'Register'))
            ->getForm();*/
    }

    public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
        'csrf_protection' => true,
        'data_class' => Users::class,
    ));
    
}
}