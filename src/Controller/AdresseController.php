<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseController extends AbstractController
{
    #[Route('/adresse', name: 'adresse.index')]
    public function index(AdresseRepository $adresseRepository,
    Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $adresseRepository->paginationQuery(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('adresse/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/adresse/{id}', name: 'adresse.showone', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->render('adresse/showone.html.twig', [
            'adresseEntite' => $adresse
        ]);
    }

    #[Route('/adresse/findallcontain', name: 'adresse.findallcontain', methods: ['POST'])]
    public function findAllContain(Request $request): Response{
        $dateProDebF = $request->get("dateProDebF");
        $dateProDebF = ($dateProDebF == "") ? "**" : $dateProDebF;
        $dateProFinF = $request->get("dateProFinF");
        $dateProFinF = ($dateProFinF == "") ? "**" : $dateProFinF;
        $dateAncDebF = $request->get("dateAncDebF");
        $dateAncDebF = ($dateAncDebF == "") ? "**" : $dateAncDebF;
        $dateAncFinF = $request->get("dateAncFinF");
        $dateAncFinF = ($dateAncFinF == "") ? "**" : $dateAncFinF;
        $adresseVF = $request->get("adresseVF");
        $adresseVF = ($adresseVF == "") ? "**" : $adresseVF;
        $cpF = $request->get("cpF");
        $cpF = ($cpF == "") ? "**" : $cpF;
        $communeF = $request->get("communeF");
        $communeF = ($communeF == "") ? "**" : $communeF;
        $section_CF = $request->get("section_CF");
        $section_CF = ($section_CF == "") ? "**" : $section_CF;
        $dernierClientF = $request->get("dernierClientF");
        $dernierClientF = ($dernierClientF == "") ? "**" : $dernierClientF;
        $nbControleF = $request->get("nbControleF");
        $nbControleF = ($nbControleF == "") ? "**" : $nbControleF;

        return $this->redirectToRoute('adresse.findallcontainReq', [
            'dateProDebF' => $dateProDebF,
            'dateProFinF' => $dateProFinF,
            'dateAncDebF' => $dateAncDebF,
            'dateAncFinF' => $dateAncFinF,
            'adresseVF' => $adresseVF,
            'cpF' => $cpF,
            'communeF' => $communeF,
            'section_CF' => $section_CF,
            'dernierClientF' => $dernierClientF,
            'nbControleF' => $nbControleF
        ]);
    }

    #[Route('/adresse/{dateProDebF}/{dateProFinF}/{dateAncDebF}/{dateAncFinF}/{adresseVF}/{cpF}/{communeF}/{section_CF}/{dernierClientF}/{nbControleF}', name: 'adresse.findallcontainReq', methods: ['GET'])]
    public function findAllContainReq( AdresseRepository $adresseRepository,
    Request $request, PaginatorInterface $paginator,
    string $dateProDebF, string $dateProFinF, string $dateAncDebF, string $dateAncFinF, string $adresseVF, string $cpF, string $communeF, string $section_CF, string $dernierClientF,
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
        $pagination = $paginator->paginate(
            $requete->getQuery(),
            $request->query->get('page', 1),
            20,
            array('wrap-queries'=>true)
            
        );
        return $this->render('adresse/index.html.twig', [
            'pagination' => $pagination,
            'dateProDebF' => $dateProDebF,
            'dateProFinF' => $dateProFinF,
            'dateAncDebF' => $dateAncDebF,
            'dateAncFinF' => $dateAncFinF,
            'adresseVF' => $adresseVF,
            'cpF' => $cpF,
            'communeF' => $communeF,
            'section_CF' => $section_CF,
            'dernierClientF' => $dernierClientF,
            'nbControleF' => $nbControleF
        ]);
    }

    #[Route('/adresse/form/new', name: 'adresse.ajout', methods: ['GET', 'POST'])]
    public function ajout(AdresseRepository $adresseRepository, Request $request): Response
    {
        $adresse = new Adresse();
        $formAdresse = $this->createForm(AdresseType::class, $adresse);

        $formAdresse->handleRequest($request);
        if ($formAdresse->isSubmitted() && $formAdresse->isValid()){
            $adresseRepository->add($adresse, true);
            $this->addFlash(
                'success',
                "Ajout de l'adresse " . $adresse->getAdresseVisite() . " " . $adresse->getCommune() . " prise en compte"
            );
            return $this->render('adresse/showone.html.twig', [
                'adresseEntite' => $adresse
            ]);
        }

        return $this->render('rendez_vous/rendez_vous.form.html.twig', [
            'adresse' => $adresse,
            'formAdresse' => $formAdresse->createView()
        ]);
    }

    #[Route('/adresse/form/edit/{id}', name: 'adresse.edit', methods: ['GET', 'POST'])]
    public function edit(Adresse $adresse, AdresseRepository $adresseRepository, Request $request): Response
    {
        $formAdresse = $this->createForm(AdresseType::class, $adresse);

        $formAdresse->handleRequest($request);
        if($formAdresse->isSubmitted() && $formAdresse->isValid()){
            $adresseRepository->add($adresse, true);
            $this->addFlash(
                'success',
                "Modification de l'adresse " . $adresse->getAdresseVisite() . ' ' . $adresse->getCommune() . ' prise en compte');
                return $this->render('adresse/showone.html.twig', [
                    'adresseEntite' => $adresse
                ]);
        }

        return $this->render('adresse/adresse.form.html.twig', [
            'adresse' => $adresse,
            'formAdresse' => $formAdresse->createView()
        ]);
    }

    #[Route('/adresse/form/suppr/{id}', name: 'adresse.suppr', methods: ['GET', 'POST'])]
    public function suppr(Adresse $adresse, AdresseRepository $adresseRepository): Response
    {   
        if ($adresse->getRendezVous()->count() > 0){
            $this->addFlash(
                'alert',
                "Suppresion de l'adresse " . $adresse->getAdresseVisite() . ' ' . $adresse->getCommune() . ' impossible tant que des rendez-vous lui sont ratachée'
            );
            return $this->render('adresse/showone.html.twig', [
                'adresseEntite' => $adresse
            ]);
        }
        $adresseRepository->remove($adresse, true);
        $this->addFlash(
            'success',
            "Suppresion de l'adresse " . $adresse->getAdresseVisite() . ' ' . $adresse->getCommune() . ' prise en compte'
        );
        return $this->redirectToRoute('rdv.index');
    }
}
