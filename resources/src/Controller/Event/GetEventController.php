<?php
namespace App\Controller\Event;

use App\Manager\EventManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetEventController extends AbstractController
{
    /** @var EventManager */
    private $eventManager;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param EventManager $eventManager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EventManager $eventManager,
        SerializerInterface $serializer
    ) {
        $this->eventManager = $eventManager;
        $this->serializer = $serializer;
    }

    /**
     * Get event by id
     *
     * @Route("/api/private/event/{eventId}", methods={"GET"})
     *
     * @OA\Tag(name="Event")
     *
     * @OA\Response(response=200, description="Event details")
     * @OA\Response(response=400, description="Event was not found")
     *
     * @param int $eventId
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(int $eventId, UserInterface $user): JsonResponse
    {
        $event = $this->eventManager->findOneBy(['id' => $eventId]);
        if (is_null($event)) {
            return new JsonResponse(['error_message' => 'Event was not found'], Response::HTTP_BAD_REQUEST);
        }

        $normalizedList = $this->serializer->serialize($event, 'json');
        return new JsonResponse(json_decode($normalizedList, true), Response::HTTP_OK);
    }
}