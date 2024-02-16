<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'client.index')]
    public function index(ClientRepository $clientRepository,
    Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $clientRepository->paginationQuery(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('client/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/client/{id}', name: 'client.showone', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/showone.html.twig', [
            'client' => $client
        ]);
    }

    #[Route('/client/findallcontain', name: 'client.findallcontain', methods: ['POST'])]
    public function findAllContain(Request $request): Response{
        $dRdvDateDebF = $request->get("dRdvDateDebF");
        $dRdvDateDebF = ($dRdvDateDebF == "") ? "**" : $dRdvDateDebF;
        $dRdvDateFinF = $request->get("dRdvDateFinF");
        $dRdvDateFinF = ($dRdvDateFinF == "") ? "**" : $dRdvDateFinF;
        $nomF = $request->get("nomF");
        $nomF = ($nomF == "") ? "**" : $nomF;
        $mailF = $request->get("mailF");
        $mailF = ($mailF == "") ? "**" : $mailF;
        $telF = $request->get("telF");
        $telF = ($telF == "") ? "**" : $telF;
        $dernierRdvAdresseF = $request->get("dernierRdvAdresseF");
        $dernierRdvAdresseF = ($dernierRdvAdresseF == "") ? "**" : $dernierRdvAdresseF;
        $dernierRdvCommuneF = $request->get("dernierRdvCommuneF");
        $dernierRdvCommuneF = ($dernierRdvCommuneF == "") ? "**" : $dernierRdvCommuneF;
        $nbRdvF = $request->get("nbRdvF");
        $nbRdvF = ($nbRdvF == "") ? "**" : $nbRdvF;

        return $this->redirectToRoute('client.findallcontainReq', [
            'dRdvDateDebF' => $dRdvDateDebF,
            'dRdvDateFinF' => $dRdvDateFinF,
            'nomF' => $nomF,
            'mailF' => $mailF,
            'telF' => $telF,
            'dernierRdvAdresseF' => $dernierRdvAdresseF,
            'dernierRdvCommuneF' => $dernierRdvCommuneF,
            'nbRdvF' => $nbRdvF
        ]);
    }

    #[Route('/client/{dRdvDateDebF}/{dRdvDateFinF}/{nomF}/{mailF}/{telF}/{dernierRdvAdresseF}/{dernierRdvCommuneF}/{nbRdvF}', name: 'client.findallcontainReq', methods: ['GET'])]
    public function findAllContainReq( ClientRepository $clientRepository,
    Request $request, PaginatorInterface $paginator,
    string $dRdvDateDebF, string $dRdvDateFinF, string $nomF, string $mailF, string $telF, string $dernierRdvAdresseF, string $dernierRdvCommuneF, string $nbRdvF): Response{
        $interup = false;
        $requete =  $clientRepository->createQueryBuilder('c')
            ->leftjoin('c.rendezVous', 'r')
            ->join('c.dernier_rdv', 'd')
            ->join('d.adresse', 'a')
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
        if($telF != "**"){
            $requete->andwhere('c.tel like :telF')
            ->setParameter('telF', '%'.$telF.'%');
            $interup = true;
        }
        if($mailF != "**"){
            $requete->andwhere('c.mail like :mailF')
            ->setParameter('mailF', '%'.$mailF.'%');
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
        $pagination = $paginator->paginate(
            $requete->getQuery()->getResult(),
            $request->query->get('page', 1),
            20
        );

        return $this->render('client/index.html.twig', [
            'pagination' => $pagination,
            'dRdvDateDebF' => $dRdvDateDebF,
            'dRdvDateFinF' => $dRdvDateFinF,
            'nomF' => $nomF,
            'mailF' => $mailF,
            'telF' => $telF,
            'dernierRdvAdresseF' => $dernierRdvAdresseF,
            'dernierRdvCommuneF' => $dernierRdvCommuneF,
            'nbRdvF' => $nbRdvF
        ]);
    }
}
