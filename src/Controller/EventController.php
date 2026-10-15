<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    #[Route('/evenement/ajouter', name: 'app_event_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $fileName = uniqid().'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/events',
                    $fileName
                );

                $event->setImage($fileName);
            }

            $event->setIsValidated(false);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/evenement/{id}/inscrire', name: 'app_event_register', methods: ['POST'])]
    public function register(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->isCsrfTokenValid('register'.$event->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();

        if (
            $event->isValidated()
            && $event->getNumberOfPlaces() > 0
            && !$event->getParticipants()->contains($user)
        ) {
            $event->addParticipant($user);
            $event->setNumberOfPlaces($event->getNumberOfPlaces() - 1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_show', [
            'id' => $event->getId(),
        ]);
    }

    #[Route('/evenement/{id}/desinscrire', name: 'app_event_unregister', methods: ['POST'])]
    public function unregister(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->isCsrfTokenValid('unregister'.$event->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($event->getParticipants()->contains($user)) {
            $event->removeParticipant($user);
            $event->setNumberOfPlaces($event->getNumberOfPlaces() + 1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_show', [
            'id' => $event->getId(),
        ]);
    }
}
