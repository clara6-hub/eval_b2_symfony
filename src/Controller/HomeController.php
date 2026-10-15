<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        EventRepository $eventRepository,
        Request $request
    ): Response {
        $categorie = $request->query->get('categorie');

        $criteres = ['isValidated' => true];

        if (in_array($categorie, ['concert', 'conference', 'atelier', 'sport'], true)) {
            $criteres['category'] = $categorie;
        }

        $events = $eventRepository->findBy($criteres, ['date' => 'ASC']);

        $events = array_filter($events, function ($event) {
            return $event->getDate() > new \DateTimeImmutable();
        });

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'categorie' => $categorie,
        ]);
    }
}
