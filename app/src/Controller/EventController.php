<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/event", name="event_index")
     */

    public function index(): Response
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
    
    
    /**
     * @Route("/event/{id}", name="event_show", requirements={"id"="\d+"})
     */
    

    public function show($id): Response
    {
        $eventId = (int)$id;
        $event = $this->entityManager->getRepository(Event::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('El evento no se encontró');
        }
        return $this->render('event/show.html.twig', ['event' => $event]);
    }

   /**
     * @Route("/event/create", name="event_create")
     */

    public function create(Request $request, FormFactoryInterface $formFactory): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $combinedDateTime = new \DateTimeImmutable(
                $event->getDate()->format('Y-m-d') . ' ' . $event->getTime()->format('H:i:s')
                );
            $event->setDateTime($combinedDateTime);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'El evento se ha creado correctamente.');

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }
}
