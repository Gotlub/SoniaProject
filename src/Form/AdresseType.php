<?php

namespace App\Form;

use DateTime;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prochaine_visite', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Prochaine visite'
            ])
            ->add('adresseVisite', null, [
                'label' =>'Adresse de la visite'])
            ->add('cp')
            ->add('commune')
            ->add('section_cadastrale', null, [
                'label' =>'Cadastre'])
            ->add('ancienne_adresse')
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
