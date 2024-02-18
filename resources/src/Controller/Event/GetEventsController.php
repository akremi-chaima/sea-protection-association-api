<?php
namespace App\Controller\Event;

use App\Manager\EventManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class GetEventsController extends AbstractController
{
    /** @var EventManager */
    private $eventManager;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param EventManager $eventManager
     * @param SerializerInterface $serializer
     */
    public function __construct(EventManager $eventManager, SerializerInterface $serializer)
    {
        $this->eventManager = $eventManager;
        $this->serializer = $serializer;
    }

    /**
     * Get events list
     *
     * @Route("/api/events", methods={"GET"})
     *
     * @OA\Tag(name="Event")
     *
     * @OA\Response(response=200, description="Events list")
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $events = $this->eventManager->findBy([], ['date' => 'DESC']);
        $normalizedList = $this->serializer->serialize($events, 'json');
        return new JsonResponse(json_decode($normalizedList, true), Response::HTTP_OK);
    }
}