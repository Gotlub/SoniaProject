<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Repository\ClientRepository;
use App\Repository\AdresseRepository;
use App\Repository\RendezVousRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CSVController extends AbstractController
{
    protected function createSpreadsheet()
    {
        $file = new Spreadsheet();
        $active_sheet = $file->getActiveSheet();
        $active_sheet->setCellValue('A1', 'Date du contrôle');
        $active_sheet->getColumnDimension('A')->setWidth(15);
        $active_sheet->setCellValue('B1', 'Numéro de dossier');
        $active_sheet->getColumnDimension('B')->setWidth(20);
        $active_sheet->setCellValue('C1', 'Facturation');
        $active_sheet->getColumnDimension('C')->setWidth(25);
        $active_sheet->setCellValue('D1', 'Date de facturation');
        $active_sheet->getColumnDimension('D')->setWidth(20);
        $active_sheet->setCellValue('E1', 'Commentaire');
        $active_sheet->getColumnDimension('E')->setWidth(30);
        $active_sheet->setCellValue('F1', 'Nom');
        $active_sheet->getColumnDimension('F')->setWidth(30);
        $active_sheet->setCellValue('G1', 'Prénom');
        $active_sheet->getColumnDimension('G')->setWidth(30);
        $active_sheet->setCellValue('H1', 'Adresse du rendez-vous');
        $active_sheet->getColumnDimension('H')->setWidth(35);
        $active_sheet->setCellValue('I1', 'Commune du rendez-vous');
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
        $active_sheet->setCellValue('V1', 'Nom Propriétaire');
        $active_sheet->getColumnDimension('V')->setWidth(30);
        $active_sheet->setCellValue('W1', 'Prénom Propriétaire');
        $active_sheet->getColumnDimension('W')->setWidth(30);
        $columnLetter = 'A';
        $active_sheet->getRowDimension(1)->setRowHeight(30);
        while( $columnLetter != 'X'){
            $active_sheet->getStyle($columnLetter.'1')->getFont()->setBold(true);
            $active_sheet->getStyle($columnLetter.'1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle($columnLetter.'1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columnLetter.'1')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
            $columnLetter++;
        }
        return $file;
    }
    function build_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }

    #[Route('/csv/rdv/{dateConDeb}/{dateConFin}/{typeCon}/{numDosier}/{clientNom}/{Adadresse}/{aDcommune}/{aDcommuneExaF}/{aDcadastre}/{prorioNomF}/{prorioCommuneF}/', name: 'csv.rdv')]
    public function rdvCSV(RendezVousRepository $rendezVousRepository,
    string $dateConDeb ,string  $dateConFin, string $typeCon, string $numDosier, string $clientNom,
    string $Adadresse, string $aDcommune, string $aDcommuneExaF, string $aDcadastre, string $prorioNomF, string $prorioCommuneF)
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
        if($aDcommuneExaF != "**"){
            $requete->andwhere('a.commune = :aDcommuneExaF')
                ->setParameter('aDcommuneExaF', $aDcommuneExaF);
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
        $requete->orderBy('r.date_controle', 'DESC');
        $lesRendezVous = $requete->getQuery()
                                ->getResult();
        $file = $this->createSpreadsheet();
        $active_sheet = $file->getActiveSheet();
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
            $active_sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle('J'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle('K'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle('P'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle('R'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $active_sheet->getStyle('T'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $count += 1;
        }
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writer = new Xlsx($file);
        $response = new StreamedResponse();
        $filename = implode( '-', ['Rdv', $dateConDeb, $dateConFin, $typeCon, $numDosier, $clientNom, $Adadresse, $aDcommune, $aDcommuneExaF, $aDcadastre, $prorioNomF, $prorioCommuneF ]);
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

    #[Route('/csv/adresse/{dateProDebF}/{dateProFinF}/{dateAncDebF}/{dateAncFinF}/{adresseVF}/{cpF}/{communeF}/{communeExaF}/{section_CF}/{dernierClientF}/{nbControleF}', name: 'csv.adresse', methods: ['GET'])]
    public function adresseCSV( AdresseRepository $adresseRepository,
    string $dateProDebF, string $dateProFinF, string $dateAncDebF, string $dateAncFinF, string $adresseVF, string $cpF, string $communeF, string $communeExaF, string $section_CF, string $dernierClientF,
    string $nbControleF): Response{
        $interup = false;
        $requete =  $adresseRepository->createQueryBuilder('a')
            ->leftjoin('a.rendez_vous', 'r')
            ->leftjoin('a.dernier_rdv', 'd')
            ->leftjoin('d.client', 'c');
        if($cpF != "**"){
            $requete->where('a.cp like :cpF')
            ->setParameter('cpF', '%'.$cpF.'%');
            $interup = true;
        }else{
            $requete ->where('1 = :triche')
            ->setParameter('triche', 1);
        }
        if($dateProDebF != "**" ){
            $requete->andwhere('a.prochaine_visite >= :datePro')
            ->setParameter('datePro', $dateProDebF);
            $interup = true;
        }
        if($dateProFinF != "**"){
            $requete->andwhere('a.prochaine_visite <= :dateProFinF')
            ->setParameter('dateProFinF', $dateProFinF);
            $interup = true;
        }
        if($dateAncDebF != "**" ){
            $requete->andwhere('d.date_controle >= :dateAnc')
            ->setParameter('dateAnc', $dateAncDebF);
            $interup = true;
        }
        if($dateAncFinF != "**"){
            $requete->andwhere('d.date_controle <= :dateAncFin')
            ->setParameter('dateAncFin', $dateAncFinF);
            $interup = true;
        }
        if($adresseVF != "**"){
            $requete->andwhere('a.adresseVisite like :adresseVF')
            ->setParameter('adresseVF', '%'.$adresseVF.'%');
            $interup = true;
        }
        if($cpF != "**"){
            $requete->andwhere('a.cp like :cpF')
            ->setParameter('cpF', '%'.$cpF.'%');
            $interup = true;
        }
        if($communeF != "**"){
            $requete->andwhere('a.commune like :communeF')
            ->setParameter('communeF', '%'.$communeF.'%');
            $interup = true;
        }
        if($communeExaF != "**"){
            $requete->andwhere('a.commune = :communeExaF')
            ->setParameter('communeExaF', $communeExaF);
            $interup = true;
        }
        if($section_CF != "**"){
            $requete->andwhere('a.section_cadastrale like :section_CF')
            ->setParameter('section_CF', '%'.$section_CF.'%');
            $interup = true;
        }
        if($dernierClientF != "**"){
            $requete->andwhere('c.nom like :dernierClientF')
            ->setParameter('dernierClientF', '%'.$dernierClientF.'%');
            $interup = true;
        }
        $requete->groupBy('a.id')
            ->orderBy('a.prochaine_visite', 'DESC');
        if($nbControleF != "**"){
            $requete->having("count(r.id) = :nbControleF")
            ->setParameter('nbControleF', $nbControleF);
            $interup = true;
        }
        if(!$interup){
            return $this->redirectToRoute('adresse.index');
        }
        $requete->OrderBy('a.commune', 'ASC');
        $LesAdresse = $requete->getQuery()
                ->getResult();
        $file = $this->createSpreadsheet();
        $active_sheet = $file->getActiveSheet();
        $count = 2;
        foreach($LesAdresse as $adresse){
            $lesRdv = $adresse->getRendezVous()->toArray();
            usort($lesRdv, [RendezVous::class, "cmp_obj"]);
            foreach ($lesRdv as $rendezVous) {
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
                $active_sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('J'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('K'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('P'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('R'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('T'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $count += 1;
            }
        }
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writer = new Xlsx($file);
        $response = new StreamedResponse();
        $filename = implode( '-', ['Adresse', $dateProDebF, $dateProFinF, $dateAncDebF, $dateAncFinF, $adresseVF, $cpF, $communeF, $communeExaF, $section_CF, $dernierClientF, $nbControleF ]);
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

    #[Route('/csv/client/{dRdvDateDebF}/{dRdvDateFinF}/{nomF}/{adresseFacF}/{communeFacF}/{dernierRdvAdresseF}/{dernierRdvCommuneF}/{nbRdvF}', name: 'csv.client', methods: ['GET'])]
    public function clientCSV( ClientRepository $clientRepository,
    string $dRdvDateDebF, string $dRdvDateFinF, string $nomF, string $adresseFacF, string $communeFacF, string $dernierRdvAdresseF, string $dernierRdvCommuneF, string $nbRdvF): Response{
        $interup = false;
        $requete =  $clientRepository->createQueryBuilder('c')
            ->leftjoin('c.rendezVous', 'r')
            ->leftjoin('c.dernier_rdv', 'd')
            ->leftjoin('d.adresse', 'a')
            ->where('1 = :triche')
            ->setParameter('triche', 1);
        if($dRdvDateDebF != "**" ){
            $requete->andwhere('d.date_controle >= :datePro')
            ->setParameter('datePro', $dRdvDateDebF);
            $interup = true;
        }
        if($dRdvDateFinF != "**"){
            $requete->andwhere('d.date_controle <= :dateProFinF')
            ->setParameter('dateProFinF', $dRdvDateFinF);
            $interup = true;
        }
        if($nomF != "**"){
            $requete->andwhere('c.nom like :nomF')
            ->setParameter('nomF', '%'.$nomF.'%');
            $interup = true;
        }
        if($adresseFacF != "**"){
            $requete->andwhere('c.adresse_facturation like :adresseFacF')
            ->setParameter('adresseFacF', '%'.$adresseFacF.'%');
            $interup = true;
        }
        if($communeFacF != "**"){
            $requete->andwhere('c.commune_facturation like :communeFacF')
            ->setParameter('communeFacF', '%'.$communeFacF.'%');
            $interup = true;
        }
        if($dernierRdvAdresseF != "**"){
            $requete->andwhere('a.adresseVisite like :dernierRdvAdresseF')
            ->setParameter('dernierRdvAdresseF', '%'.$dernierRdvAdresseF.'%');
            $interup = true;
        }
        if($dernierRdvCommuneF != "**"){
            $requete->andwhere('a.commune like :dernierRdvCommuneF')
            ->setParameter('dernierRdvCommuneF', '%'.$dernierRdvCommuneF.'%');
            $interup = true;
        }
        $requete->groupBy('c.id')
            ->orderBy('c.nom', 'ASC');
        if($nbRdvF != "**"){
            $requete->having("count(r.id) = :nbRdvF")
            ->setParameter('nbRdvF', $nbRdvF);
            $interup = true;
        }
        if(!$interup){
            return $this->redirectToRoute('client.index');
        }
        $requete->OrderBy('c.nom', 'ASC');
        $LesClients = $requete->getQuery()
                ->getResult();
        $file = $this->createSpreadsheet();
        $active_sheet = $file->getActiveSheet();
        $count = 2;
        foreach($LesClients as $client){
            $lesRdv = $client->getRendezVous()->toArray();
            usort($lesRdv, [RendezVous::class, "cmp_obj"]);
            foreach ($lesRdv as $rendezVous) {
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
                $active_sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('J'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('K'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('P'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('R'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $active_sheet->getStyle('T'.$count)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $count += 1;
            }
        }
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writer = new Xlsx($file);
        $response = new StreamedResponse();
        $filename = implode( '-', ['Client', $dRdvDateDebF, $dRdvDateFinF, $nomF, $adresseFacF, $communeFacF, $dernierRdvAdresseF, $dernierRdvCommuneF, $nbRdvF]);
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

   
}
