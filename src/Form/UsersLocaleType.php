<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersLocaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', ChoiceType::class, array(
            'choices' => array(
                'English' => 'en',
                'Spanish' => 'es_ES',
            ),
            'attr' => array('onChange' => 'saveLocale(this)'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
        'csrf_protection' => true,
        'csrf_field_name' => '_token',
        'csrf_token_id'   => 'form_locale'
    ));
}
}