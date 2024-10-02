<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TrickRepository $repository, Request $request): Response
    {
        $paginator = $repository->findPaginate($request);
        $tricks = $paginator['datas'];
        $count = ceil($paginator['count'] / 10);
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
            'tricks' => $tricks,
            'count' => $count,
        ]);
    }

    #[Route('/more_tricksJS/{page}', name: 'home_paginated')]
    public function indexPaginated(TrickRepository $repository, Request $request): Response
    {
        $paginator = $repository->findPaginate($request);
        $tricks = $paginator['datas'];
        return $this->render('trick/paginatedTricks.html.twig', [
            'controller_name' => 'TrickController',
            'tricks' => $tricks,
        ]);
    }

    #[Route('/trick/create/', name: 'create_trick')]
    public function createTrick(): Response
    {
        $form = $this->createForm(TrickFormType::class);
        return $this->render(
            'trick/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/trick/detail/{id}', name: 'detail_trick')]
    public function detailTrick(int $id, TrickRepository $repo): Response
    {
        $trickWithJoinData = $repo->findOneWithJoins($id);
##TODO : Rajouter, pour les commentaires, la pagination, voir s'il faut faire une autre requête où si je peux mélanger les deux.
        return $this->render('trick/detail.html.twig', [
            'trick' => $trickWithJoinData
        ]);
    }

}

