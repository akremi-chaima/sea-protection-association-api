<?php
namespace App\Controller\Participant;

use App\DTO\Participant\AddParticipantDTO;
use App\Entity\Event;
use App\Entity\Participant;
use App\Manager\EventManager;
use App\Manager\ParticipantManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddParticipantController extends AbstractController
{
    /** @var ParticipantManager */
    private $participantManager;

    /** @var EventManager */
    private $eventManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param ParticipantManager $participantManager
     * @param EventManager $eventManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ParticipantManager $participantManager,
        EventManager $eventManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->participantManager = $participantManager;
        $this->serializer = $serializer;
        $this->eventManager = $eventManager;
        $this->validator = $validator;
    }

    /**
     * Add participant
     *
     * @Route("/api/participant", methods={"POST"})
     *
     * @OA\Tag(name="Participant")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              required={"firstName", "lastName", "phoneNumber", "eventId", "email"},
     *              @OA\Property(property="firstName", type="string"),
     *              @OA\Property(property="lastName", type="string"),
     *              @OA\Property(property="phoneNumber", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="eventId", type="integer"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="Participant added")
     * @OA\Response(response=400, description="The event was not found | Invalid data")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var AddParticipantDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), AddParticipantDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        /** @var Event|null $event */
        $event = $this->eventManager->findOneBy(['id' => $dto->getEventId()]);
        if (is_null($event) ) {
            return new JsonResponse(['error_message' => 'The event was not found.'], Response::HTTP_BAD_REQUEST);
        }

        $participant = (new Participant())
            ->setFirstName($dto->getFirstName())
            ->setPhoneNumber($dto->getPhoneNumber())
            ->setEmail($dto->getEmail())
            ->setLastName($dto->getLastName())
            ->setEvent($event);

        $this->participantManager->save($participant);

        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}