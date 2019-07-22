<?php

namespace App\Form;

use App\Entity\Ducks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('email')
            ->add('password')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Admin" => "ROLE_ADMIN",
                ],
                "multiple" => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ducks::class,
        ]);
    }
}
