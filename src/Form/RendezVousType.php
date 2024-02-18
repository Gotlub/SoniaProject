<?php

namespace App\Form;

use DateTime;
use App\Entity\Client;
use App\Entity\Adresse;
use App\Entity\RendezVous;
use Doctrine\ORM\QueryBuilder;
use App\Repository\ClientRepository;
use App\Repository\AdresseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('facturation')
            ->add('date_facturation')
            ->add('commentaire')
            ->add('status_dossier')
            ->add('type_controle')
            ->add('num_dossier')
            ->add('date_controle', DateType::class, [
                'widget' => 'single_text',
                'data' => isset($options['data']) &&
                    $options['data']->getDateControle() != null ? $options['data']->getDateControle() : new DateTime('now'),
                'label' => 'Date'
            ])
            ->add('type_traitement')
            ->add('type_installation')
            ->add('rejet_inf')
            ->add('conformite')
            ->add('impact')
            ->add('type_RPQS')
            ->add('EF_etudes')
            ->add('EDN')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'query_builder' => function (ClientRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'required' => false,
                'placeholder' => 'client',
                'autocomplete'=>true
            ])
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'query_builder' => function (AdresseRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.commune', 'ASC')
                        ->addOrderBy('a.adresseVisite', 'ASC');
                },
                'required' => false,
                'placeholder' => 'adresse',
                'autocomplete'=>true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
