<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Repository\AdresseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdresseController extends AbstractController
{
    #[Route('/adresse', name: 'adresse.index')]
    public function index(AdresseRepository $adresseRepository,
    Request $request, PaginatorInterface $paginator): Response
    {
       //dd( $adresseRepository->paginationQuery());
        $pagination = $paginator->paginate(
            $adresseRepository->paginationQuery(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('adresse/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/adresse/findallcontain', name: 'adresse.findallcontain', methods: ['POST'])]
    public function findAllContain(Request $request): Response{
        $dateProDebF = $request->get("dateProDebF");
        $dateProDebF = ($dateProDebF == "") ? "**" : $dateProDebF;
        $dateProFinF = $request->get("dateProFinF");
        $dateProFinF = ($dateProFinF == "") ? "**" : $dateProFinF;
        $adresseVF = $request->get("adresseVF");
        $adresseVF = ($adresseVF == "") ? "**" : $adresseVF;
        $cpF = $request->get("cpF");
        $cpF = ($cpF == "") ? "**" : $cpF;
        $communeF = $request->get("communeF");
        $communeF = ($communeF == "") ? "**" : $communeF;
        $section_CF = $request->get("section_CF");
        $section_CF = ($section_CF == "") ? "**" : $section_CF;
        $ancienneAF = $request->get("ancienneAF");
        $ancienneAF = ($ancienneAF == "") ? "**" : $ancienneAF;
        $nbControleF = $request->get("nbControleF");
        $nbControleF = ($nbControleF == "") ? "**" : $nbControleF;

        return $this->redirectToRoute('adresse.findallcontainReq', [
            'dateProDebF' => $dateProDebF,
            'dateProFinF' => $dateProFinF,
            'adresseVF' => $adresseVF,
            'cpF' => $cpF,
            'communeF' => $communeF,
            'section_CF' => $section_CF,
            'ancienneAF' => $ancienneAF,
            'nbControleF' => $nbControleF
        ]);
    }

    #[Route('/adresse/{dateProDebF}/{dateProFinF}/{adresseVF}/{cpF}/{communeF}/{section_CF}/{ancienneAF}/{nbControleF}', name: 'adresse.findallcontainReq', methods: ['GET'])]
    public function findAllContainReq( AdresseRepository $adresseRepository,
    Request $request, PaginatorInterface $paginator,
    string $dateProDebF, string $dateProFinF, string $adresseVF, string $cpF, string $communeF, string $section_CF, string $ancienneAF,
    string $nbControleF): Response{
        $interup = false;
        $requete =  $adresseRepository->createQueryBuilder('a')
            ->leftjoin('a.rendez_vous', 'r');
        if($cpF != "**"){
            $requete->where('a.cp like :cpF')
            ->setParameter('cpF', '%'.$cpF.'%');
            $interup = true;
        }else{
            $requete->where('a.cp like :cp6')
            ->setParameter('cp6', '%6%');
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
        if($ancienneAF != "**"){
            $requete->andwhere('a.ancienne_adresse like :ancienneAF')
            ->setParameter('ancienneAF', '%'.$ancienneAF.'%');
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
            $requete->getQuery()->getResult(),
            $request->query->get('page', 1),
            20
        );


        return $this->render('adresse/index.html.twig', [
            'pagination' => $pagination,
            'dateProDebF' => $dateProDebF,
            'dateProFinF' => $dateProFinF,
            'adresseVF' => $adresseVF,
            'cpF' => $cpF,
            'communeF' => $communeF,
            'section_CF' => $section_CF,
            'ancienneAF' => $ancienneAF,
            'nbControleF' => $nbControleF
        ]);
    }
}
