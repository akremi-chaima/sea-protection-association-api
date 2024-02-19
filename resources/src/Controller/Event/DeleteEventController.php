<?php
namespace App\Controller\Event;

use App\Manager\EventManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class DeleteEventController extends AbstractController
{
    /** @var EventManager */
    private $eventManager;

    /**
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Delete event
     *
     * @Route("/api/event/{eventId}", methods={"DELETE"})
     *
     * @OA\Tag(name="Event")
     *
     * @OA\Response(response=200, description="Event deleted")
     * @OA\Response(response=400, description="Event was not found")
     *
     * @param int $eventId
     * @return JsonResponse
     */
    public function __invoke(int $eventId): JsonResponse
    {
        $event = $this->eventManager->findOneBy(['id' => $eventId]);
        if (is_null($event)) {
            return new JsonResponse(['error_message' => 'Event was not found'], Response::HTTP_BAD_REQUEST);
        }

        $this->eventManager->delete($event);

        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}