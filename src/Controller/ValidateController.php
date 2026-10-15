<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ValidateController extends AbstractController
{
    #[Route('/validate', name: 'app_validate')]
    public function index(EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        

        $events = $eventRepository->findBy(
            ['isValidated' => false],
            ['date' => 'ASC']
        );

        return $this->render('validate/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/validate/{id}', name: 'app_validate_event', methods: ['POST'])]
    public function validate(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isCsrfTokenValid(
            'validate'.$event->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        $event->setIsValidated(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_validate');
    }
}
