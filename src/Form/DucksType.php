<?php

namespace App\Form;

use App\Entity\Ducks;
use function PHPSTORM_META\type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DucksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('duckname')
            ->add('email', EmailType::class)
            ->add('newpassword', RepeatedType::class, [
                "first_name" => "password",
                "second_name" => "passwordCheck",
                "type" => PasswordType::class,
                "required" => $options["password_set"],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ducks::class,
            'password_set' => false,
        ]);
    }
}
