<?php
namespace App\Controller\Event;

use App\DTO\Event\CreateEventDTO;
use App\Entity\Event;
use App\Manager\EventManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class CreateEventController extends AbstractController
{
    /** @var EventManager */
    private $eventManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param EventManager $eventManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EventManager $eventManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->eventManager = $eventManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Create event
     *
     * @Route("/api/event", methods={"POST"})
     *
     * @OA\Tag(name="Event")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              required={"title", "address", "date"},
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="address", type="string"),
     *              @OA\Property(property="date", type="string"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="Event created")
     * @OA\Response(response=400, description="Invalid data")
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var CreateEventDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CreateEventDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        $event = (new Event())
            ->setAddress($dto->getAddress())
            ->setDate(new \DateTime($dto->getDate()))
            ->setTitle($dto->getTitle());
        $this->eventManager->save($event);
        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}