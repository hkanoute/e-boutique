<?php

namespace App\Form;

use App\Entity\CustomerAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',ChoiceType::class,[
                'choices' => [
                    'Facturation' => 'Facturation',
                    'Livraison' => 'Livraison'
                ],
                'attr' => ['class' => 'form-control ']
            ])
            ->add('name', TextType::class,['attr' => ['class' => 'form-control ']])
            ->add('firstName',TextType::class,['attr' => ['class' => 'form-control ']])
            ->add('address',TextType::class,['attr' => ['class' => 'form-control ']])
            ->add('Zip', NumberType::class, ['attr' => ['class' => 'form-control ']])
            ->add('city',TextType::class,['attr' => ['class' => 'form-control ']])
            ->add('country',TextType::class,['attr' => ['class' => 'form-control ']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAddress::class,
        ]);
    }
}
