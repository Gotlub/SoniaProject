<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CSVController extends AbstractController
{

    #[Route('/csv/rdv/{dateConDeb}/{dateConFin}/{typeCon}/{numDosier}/{clientNom}/{Adadresse}/{aDcommune}/{aDcadastre}/{prorioNomF}/{prorioCommuneF}/', name: 'csv.rdv')]
    public function rdvCSV(Request $request, RendezVousRepository $rendezVousRepository,
    string $dateConDeb ,string  $dateConFin, string $typeCon, string $numDosier, string $clientNom,
    string $Adadresse, string $aDcommune, string $aDcadastre, string $prorioNomF, string $prorioCommuneF): Response
    {
        //dd( $dateConDeb ,  $dateConFin,  $typeCon,  $numDosier,  $clientNom, $Adadresse,  $aDcommune,  $aDcadastre,  $prorioNomF,  $prorioCommuneF);
        $interup = false;
        $requete = $rendezVousRepository->createQueryBuilder('r')
                    ->join('r.client', 'c')
                    ->join('r.adresse', 'a')
                    ->where('1 = :triche')
                    ->setParameter('triche', 1);
        if($dateConDeb != "**" ){
            $requete->andwhere('r.date_controle >= :dateConDeb')
            ->setParameter('dateConDeb', $dateConDeb);
            $interup = true;
        }
        if($dateConFin != "**"){
            $requete->andwhere('r.date_controle <= :dateConFin')
                ->setParameter('dateConFin', $dateConFin);
            $interup = true;
        }
        if($typeCon != "**"){
            $requete->andwhere('r.type_controle like :typeCon')
                ->setParameter('typeCon', '%'.$typeCon.'%');
            $interup = true;
        }
        if($numDosier != "**"){
            $requete->andwhere('r.num_dossier like :numDosier')
                ->setParameter('numDosier', '%'.$numDosier.'%');
            $interup = true;
        }
        if($clientNom != "**"){
            $requete->andwhere('c.nom like :clientNom')
                ->setParameter('clientNom', '%'.$clientNom.'%');
            $interup = true;
        }
        if($Adadresse != "**"){
            $requete->andwhere('a.adresseVisite like :Adadresse')
                ->setParameter('Adadresse', '%'.$Adadresse.'%');
            $interup = true;
        }
        if($aDcommune != "**"){
            $requete->andwhere('a.commune like :aDcommune')
                ->setParameter('aDcommune', '%'.$aDcommune.'%');
            $interup = true;
        }
        if($aDcadastre != "**"){
            $requete->andwhere('a.section_cadastrale like :aDcadastre')
                ->setParameter('aDcadastre', '%'.$aDcadastre.'%');
            $interup = true;
        }
        if($prorioNomF != "**"){
            $requete->andwhere('r.nom_propriaitaire like :prorioNomF')
                ->setParameter('prorioNomF', '%'.$prorioNomF.'%');
            $interup = true;
        }
        if($prorioCommuneF != "**"){
            $requete->andwhere('r.commune_facturation like :prorioCommuneF')
                ->setParameter('prorioCommuneF', '%'.$prorioCommuneF.'%');
            $interup = true;
        }
        if(!$interup) {
            return $this->redirectToRoute('rdv.index');
        }
        $lesRendezVous = $requete->getQuery()
                                ->getResult();
        $fp = fopen('php://temp', 'w');
        $csvData = "Date du contrôle ; Numero de dossier; Facturation; Date de facturation; Commentaire; Nom; Prenom; Adresse rdv ; Commune Rdv, Code postal, Cadastre, Type du contrôle; Type de traitement; Type d'instalation ; Rejet infiltration; Conformite; Impact; Type RPQS; Adresse de facturation; Cp facturation; Commune facturation; Nom Propriaitaire; Prenom Propriaitaire " . PHP_EOL;
        foreach ($lesRendezVous as $rendezVous) {
            $csvData .= implode(';', [
             $rendezVous->getDateControle()->format('Y-m-d'),
             str_replace("\n", "", $rendezVous->getNumDossier()),
             str_replace("\n", "", ($rendezVous->getFacturation() == null)? "" : $rendezVous->getFacturation()),
             str_replace("\n", "", ($rendezVous->getDateFacturation() == null)? "" : $rendezVous->getDateFacturation()),
             str_replace("\n", "", ($rendezVous->getCommentaire() == null)? "" : $rendezVous->getCommentaire()),
             str_replace("\n", "", (($rendezVous->getClient() != null)? $rendezVous->getClient()->getNom() : '')),
             str_replace("\n", "", (($rendezVous->getClient() != null)? $rendezVous->getClient()->getPrenom() : '')),
             str_replace("\n", "", (($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getNumero() . ' ' . $rendezVous->getAdresse()->getAdresseVisite() : '')),
             str_replace("\n", "", (($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getCommune() : '')),
             str_replace("\n", "", (($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getCp() : '')),
             str_replace("\n", "", (($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getSectionCadastrale() : '')),
             str_replace("\n", "", ($rendezVous->getTypeControle() == null)? "" : $rendezVous->getTypeControle()),
             str_replace("\n", "", ($rendezVous->getTypeTraitement() == null)? "" : $rendezVous->getTypeTraitement()),
             str_replace("\n", "", ($rendezVous->getTypeInstallation() == null)? "" : $rendezVous->getTypeInstallation()),
             str_replace("\n", "", ($rendezVous->getRejetInf() == null)? "" : $rendezVous->getRejetInf()),
             str_replace("\n", "", ($rendezVous->getConformite() == null)? "" : $rendezVous->getConformite()),
             str_replace("\n", "", ($rendezVous->getImpact() == null)? "" : $rendezVous->getImpact()),
             str_replace("\n", "", ($rendezVous->getTypeRPQS() == null)? "" : $rendezVous->getTypeRPQS()),
             str_replace("\n", "", ($rendezVous->getAdresseFacturation() == null)? "" : $rendezVous->getAdresseFacturation()),
             str_replace("\n", "", ($rendezVous->getCpFacturation() == null)? "" : $rendezVous->getCpFacturation()),
             str_replace("\n", "", ($rendezVous->getCommuneFacturation() == null)? "" : $rendezVous->getCommuneFacturation()),
             str_replace("\n", "", ($rendezVous->getNomPropriaitaire() == null)? "" : $rendezVous->getNomPropriaitaire()),
             str_replace("\n", "", ($rendezVous->getPrenomPropriaitaire() == null)? "" : $rendezVous->getPrenomPropriaitaire()),
             ]).PHP_EOL;
        }
        //dd($csvData);
        $name = implode( '-', ['Rdv', $dateConDeb, $dateConFin, $typeCon, $numDosier, $clientNom, $Adadresse, $aDcommune, $aDcadastre, $prorioNomF, $prorioCommuneF ]);
        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv ; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $name . '".csv"');

        return $response;
    }
}
