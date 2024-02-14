<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
    #[Route('/', name: 'rdv.index', methods: ['GET', 'POST'])]
    public function index(RendezVousRepository $rendezVousRepository,
    Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $rendezVousRepository->paginationQuery(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('rendez_vous/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/rdv/{id}', name: 'rdv.showone', methods: ['GET', 'POST'])]
    public function show(RendezVous $rendezVous): Response
    {
        return $this->render('rendez_vous/showone.html.twig', [
            'rendezVous' => $rendezVous,
        ]);
    }

    #[Route('/rendezvous/findallcontain', name: 'rdv.findallcontain', methods: ['POST'])]
    public function findAllContain(Request $request): Response{
        $dateConDeb = $request->get("dateConDeb");
        $dateConDeb = ($dateConDeb == "") ? "**" : $dateConDeb;
        $dateConFin = $request->get("dateConFin");
        $dateConFin = ($dateConFin == "") ? "**" : $dateConFin;
        $status = $request->get("status");
        $status = ($status == "") ? "**" : $status;
        $statusD = $request->get("statusD");
        $statusD = ($statusD == "") ? "**" : $statusD;
        $typeCon = $request->get("typeCon");
        $typeCon = ($typeCon == "") ? "**" : $typeCon;
        $numDosier = $request->get("numDosier");
        $numDosier = ($numDosier == "") ? "**" : $numDosier;
        $clientNom = $request->get("clientNom");
        $clientNom = ($clientNom == "") ? "**" : $clientNom;
        $Adadresse = $request->get("Adadresse");
        $Adadresse = ($Adadresse == "") ? "**" : $Adadresse;
        $aDcommune = $request->get("aDcommune");
        $aDcommune = ($aDcommune == "") ? "**" : $aDcommune;
        $aDcadastre = $request->get("aDcadastre");
        $aDcadastre = ($aDcadastre == "") ? "**" : $aDcadastre;
        $efFiltre = $request->get("efFiltre");
        $efFiltre = ($efFiltre == "") ? "**" : $efFiltre;
        $ednFiltre = $request->get("ednFiltre");
        $ednFiltre = ($ednFiltre == "") ? "**" : $ednFiltre;

        return $this->redirectToRoute('rdv.findallcontainReq', [
            'dateConDeb' => $dateConDeb,
            'dateConFin' => $dateConFin,
            'status' => $status,
            'statusD' => $statusD,
            'typeCon' => $typeCon,
            'numDosier' => $numDosier,
            'clientNom' => $clientNom,
            'Adadresse' => $Adadresse,
            'aDcommune' => $aDcommune,
            'aDcadastre' => $aDcadastre,
            'efFiltre' => $efFiltre,
            'ednFiltre' => $ednFiltre
        ]);
    }

    #[Route('/rdv/{dateConDeb}/{dateConFin}/{status}/{statusD}/{typeCon}/{numDosier}/{clientNom}/{Adadresse}/{aDcommune}/{aDcadastre}/{efFiltre}/{ednFiltre}', name: 'rdv.findallcontainReq', methods: ['GET'])]
    public function findAllContainReq( RendezVousRepository $rendezVousRepository,
    Request $request, PaginatorInterface $paginator,
    string $dateConDeb ,string  $dateConFin,string  $status,string $statusD,string $typeCon,string $numDosier,string $clientNom,
    string $Adadresse, string $aDcommune, string $aDcadastre, string $efFiltre, string $ednFiltre): Response{
        $params = array();
        $eFiltres = array();
        $dateCon = array();
        if($dateConDeb != "**" ){
            $dateCon += array("dateConDeb" => ">= '$dateConDeb'");
        }
        if($dateConFin != "**"){
            $dateCon += array("dateConFin" => "<= '$dateConFin'");
        }
        if($status != "**"){
            $params += array("status" => $status);
        }
        if($statusD != "**"){
            $params += array("status_dossier" => $statusD);
        }
        if($typeCon != "**"){
            $params += array("type_controle" => $typeCon);
        }
        if($numDosier != "**"){
            $params += array("num_dossier" => $numDosier);
        }
        if($clientNom != "**"){
            $params += array("c.nom" => $clientNom);
        }
        if($Adadresse != "**"){
            $params += array("a.adresse" => $Adadresse);
        }
        if($aDcommune != "**"){
            $params += array("a.commune" => $aDcommune);
        }
        if($aDcadastre != "**"){
            $params += array("a.section_cadastrale" => $aDcadastre);
        }
        if($efFiltre != "**"){
            $params += array("ef_etudes" => "not null");
        }
        if($ednFiltre != "**"){
            $params += array("edn" => "not null");
        }

        if(count($params) == 0 && count($eFiltres) == 0 && count($dateCon)) {
            return $this->redirectToRoute('app_engagement.index');
        }
        $pagination = $paginator->paginate(
            $rendezVousRepository->paginationQueryComplex($params, $dateCon, $eFiltres)->getResult(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('rendez_vous/index.html.twig', [
            'pagination' => $pagination,
            'dateConDeb' => $dateConDeb,
            'dateConFin' => $dateConFin,
            'status' => $status,
            'statusD' => $statusD,
            'typeCon' => $typeCon,
            'numDosier' => $numDosier,
            'clientNom' => $clientNom,
            'Adadresse' => $Adadresse,
            'aDcommune' => $aDcommune,
            'aDcadastre' => $aDcadastre,
            'efFiltre' => $efFiltre,
            'ednFiltre' => $ednFiltre
        ]);
    }

}
