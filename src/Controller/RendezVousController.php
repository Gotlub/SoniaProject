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
    #[Route('/', name: 'rdv.index', methods: ['GET'])]
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
}
