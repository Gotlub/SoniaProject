<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CSVController extends AbstractController
{

    #[Route('/csv/rdv/{dateConDeb}/{dateConFin}/{typeCon}/{numDosier}/{clientNom}/{Adadresse}/{aDcommune}/{aDcadastre}/{prorioNomF}/{prorioCommuneF}/', name: 'csv.rdv')]
    public function rdvCSV(Request $request, RendezVousRepository $rendezVousRepository,
    string $dateConDeb ,string  $dateConFin, string $typeCon, string $numDosier, string $clientNom,
    string $Adadresse, string $aDcommune, string $aDcadastre, string $prorioNomF, string $prorioCommuneF)
    {
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
        $file = new Spreadsheet();
        $active_sheet = $file->getActiveSheet();
        $active_sheet->setCellValue('A1', 'Date du contrôle');
        $active_sheet->getColumnDimension('A')->setWidth(15);
        $active_sheet->setCellValue('B1', 'Numero de dossier');
        $active_sheet->getColumnDimension('B')->setWidth(20);
        $active_sheet->setCellValue('C1', 'Facturation');
        $active_sheet->getColumnDimension('C')->setWidth(25);
        $active_sheet->setCellValue('D1', 'Date de facturation');
        $active_sheet->getColumnDimension('D')->setWidth(20);
        $active_sheet->setCellValue('E1', 'Commentaire');
        $active_sheet->getColumnDimension('E')->setWidth(30);
        $active_sheet->setCellValue('F1', 'Nom');
        $active_sheet->getColumnDimension('F')->setWidth(30);
        $active_sheet->setCellValue('G1', 'Prenom');
        $active_sheet->getColumnDimension('G')->setWidth(30);
        $active_sheet->setCellValue('H1', 'Adresse rdv');
        $active_sheet->getColumnDimension('H')->setWidth(35);
        $active_sheet->setCellValue('I1', 'Commune Rdv');
        $active_sheet->getColumnDimension('I')->setWidth(25);
        $active_sheet->setCellValue('J1', 'Code postal');
        $active_sheet->getColumnDimension('J')->setWidth(15);
        $active_sheet->setCellValue('K1', 'Cadastre');
        $active_sheet->getColumnDimension('K')->setWidth(30);
        $active_sheet->setCellValue('L1', 'Type du contrôle');
        $active_sheet->getColumnDimension('L')->setWidth(20);
        $active_sheet->setCellValue('M1', 'Type de traitement');
        $active_sheet->getColumnDimension('M')->setWidth(30);
        $active_sheet->setCellValue('N1', "Type d'instalation");
        $active_sheet->getColumnDimension('N')->setWidth(25);
        $active_sheet->setCellValue('O1', 'Rejet infiltration');
        $active_sheet->getColumnDimension('O')->setWidth(25);
        $active_sheet->setCellValue('P1', 'Conformite');
        $active_sheet->getColumnDimension('P')->setWidth(25);
        $active_sheet->setCellValue('Q1', 'Impact');
        $active_sheet->getColumnDimension('Q')->setWidth(25);
        $active_sheet->setCellValue('R1', 'Type RPQS');
        $active_sheet->getColumnDimension('R')->setWidth(15);
        $active_sheet->setCellValue('S1', "Adresse de facturation");
        $active_sheet->getColumnDimension('S')->setWidth(35);
        $active_sheet->setCellValue('T1', 'Cp facturation');
        $active_sheet->getColumnDimension('T')->setWidth(15);
        $active_sheet->setCellValue('U1', 'Commune facturation');
        $active_sheet->getColumnDimension('U')->setWidth(25);
        $active_sheet->setCellValue('V1', 'Nom Propriaitaire');
        $active_sheet->getColumnDimension('V')->setWidth(30);
        $active_sheet->setCellValue('W1', 'Prenom Propriaitaire');
        $active_sheet->getColumnDimension('W')->setWidth(30);
        $count = 2;
        foreach ($lesRendezVous as $rendezVous) {
            $active_sheet->setCellValue('A' . $count, $rendezVous->getDateControle()->format('Y-m-d'));
            $active_sheet->setCellValue('B' . $count, $rendezVous->getNumDossier());
            $active_sheet->setCellValue('C' . $count, $rendezVous->getFacturation());
            $active_sheet->setCellValue('D' . $count, $rendezVous->getDateFacturation());
            $active_sheet->setCellValue('E' . $count, $rendezVous->getCommentaire());
            $active_sheet->setCellValue('F' . $count, ($rendezVous->getClient() != null)? $rendezVous->getClient()->getNom() : '');
            $active_sheet->setCellValue('G' . $count, ($rendezVous->getClient() != null)? $rendezVous->getClient()->getPrenom() : '');
            $active_sheet->setCellValue('H' . $count, ($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getNumero() . ' ' . $rendezVous->getAdresse()->getAdresseVisite() : '');
            $active_sheet->setCellValue('I' . $count, ($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getCommune() : '');
            $active_sheet->setCellValue('J' . $count, ($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getCp() : '');
            $active_sheet->setCellValue('K' . $count, ($rendezVous->getAdresse() != null)? $rendezVous->getAdresse()->getSectionCadastrale() : '');
            $active_sheet->setCellValue('L' . $count, $rendezVous->getTypeControle());
            $active_sheet->setCellValue('M' . $count, $rendezVous->getTypeTraitement());
            $active_sheet->setCellValue('N' . $count, $rendezVous->getTypeInstallation());
            $active_sheet->setCellValue('O' . $count, $rendezVous->getRejetInf());
            $active_sheet->setCellValue('P' . $count, $rendezVous->getConformite());
            $active_sheet->setCellValue('Q' . $count, $rendezVous->getImpact());
            $active_sheet->setCellValue('R' . $count, $rendezVous->getTypeRPQS());
            $active_sheet->setCellValue('S' . $count, $rendezVous->getAdresseFacturation());
            $active_sheet->setCellValue('T' . $count, $rendezVous->getCpFacturation());
            $active_sheet->setCellValue('U' . $count, $rendezVous->getCommuneFacturation());
            $active_sheet->setCellValue('V' . $count, $rendezVous->getNomPropriaitaire());
            $active_sheet->setCellValue('W' . $count, $rendezVous->getPrenomPropriaitaire());
            $count += 1;
        }
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writer = new Xlsx($file);
        $response = new StreamedResponse();
        $filename = implode( '-', ['Rdv', $dateConDeb, $dateConFin, $typeCon, $numDosier, $clientNom, $Adadresse, $aDcommune, $aDcadastre, $prorioNomF, $prorioCommuneF ]);
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename . '".xlsx"');
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCallback(function() use ($writer) {
            $writer->save('php://output');
        });

        return $response;
    }

    #[Route('/csv2/rdv/{dateConDeb}/{dateConFin}/{typeCon}/{numDosier}/{clientNom}/{Adadresse}/{aDcommune}/{aDcadastre}/{prorioNomF}/{prorioCommuneF}/', name: 'csv.rdv2')]
    public function rdvCSV2(Request $request, RendezVousRepository $rendezVousRepository,
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
