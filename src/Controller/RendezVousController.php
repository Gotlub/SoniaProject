<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        //dd($pagination);
        return $this->render('rendez_vous/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/rdv/{id}', name: 'rdv.showone', methods: ['GET', 'POST'])]
    public function show(RendezVous $rendezVous): Response
    {
       //dd($rendezVous);
        return $this->render('rendez_vous/showone.html.twig', [
            'rendezVous' => $rendezVous
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
        if($status != "**"){
            $requete->andwhere('r.status like :status')
                ->setParameter('status', '%'.$status.'%');
            $interup = true;
        }
        if($statusD != "**"){
            $requete->andwhere('r.status_dossier like :statusD')
                ->setParameter('statusD', '%'.$statusD.'%');
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
            $requete->andwhere('a.adresse like :Adadresse')
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
        if($efFiltre != "**"){
            $requete->andwhere('r.EF_etudes is not :aDcadastre')
                ->setParameter('null', 'null');
            $interup = true;
        }
        if($ednFiltre != "**"){
            $requete->andwhere('r.EDN is not :aDcadastre')
                ->setParameter('null', 'null');
            $interup = true;
        }
        if(!$interup) {
            return $this->redirectToRoute('rdv.index');
        }
        $pagination = $paginator->paginate(
            $requete->getQuery(),
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

    #[Route('/rdv/form/new', name: 'rdv.ajout', methods: ['GET', 'POST'])]
    public function ajout(RendezVousRepository $rendezVousRepository, Request $request): Response
    {
        $rendez_vous = new RendezVous();
        $formRdv = $this->createForm(RendezVousType::class, $rendez_vous);

        $formRdv->handleRequest($request);
        if ($formRdv->isSubmitted() && $formRdv->isValid()){
            $rendezVousRepository->add($rendez_vous, true);
            $this->addFlash(
                'success',
                'Ajout du rendez-vous ' . $rendez_vous->getNumDossier() . " prise en compte"
            );
            return $this->redirectToRoute('rdv.index');
        }

        return $this->render('rendez_vous/rendez_vous.form.html.twig', [
            'rendez_vous' => $rendez_vous,
            'formRdv' => $formRdv->createView()
        ]);
    }

    #[Route('/rdv/form/edit/{id}', name: 'rdv.ajout', methods: ['GET', 'POST'])]
    public function edit(RendezVous $rendezVous, RendezVousRepository $rendezVousRepository, Request $request): Response
    {
        $formRdv = $this->createForm(RendezVousType::class, $rendezVous);

        $formRdv->handleRequest($request);
        if($formRdv->isSubmitted() && $formRdv->isValid()){
            $rendezVousRepository->add($rendezVous, true);
            $this->addFlash(
                'success',
                'Modification du rendez-vous ' . $rendezVous->getNumDossier() . ' prise en compte');
            return $this->redirectToRoute('rdv.index');
        }

        return $this->render('rendez_vous/rendez_vous.form.html.twig', [
            'rendez_vous' => $rendezVous,
            'formRdv' => $formRdv->createView()
        ]);
    }

}
