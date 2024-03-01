<?php
namespace App\Controller\Contact;

use App\DTO\Contact\ContactDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ContactController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var MailerInterface */
    private $mailer;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param MailerInterface $mailer
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->mailer = $mailer;
    }

    /**
     * Send contact email
     *
     * @Route("/api/contact", methods={"POST"})
     *
     * @OA\Tag(name="Contact")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              required={"lastName", "email", "message", "phoneNumber"},
     *              @OA\Property(property="lastName", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="phoneNumber", type="string"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="Email send")
     * @OA\Response(response=400, description="Invalid data")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var ContactDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), ContactDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        $email = (new Email())
            ->to(getenv('CONTACT_MAIL'))
            ->subject('Contact')
            ->from(getenv('CONTACT_MAIL'))
            ->html('
                <div>Nom: '.$dto->getLastName().'</div>
                <div>Email: '.$dto->getEmail().'</div>
                <div>Téléphone: '.$dto->getPhoneNumber().'</div>
                <div>Message: '.$dto->getMessage().'</div>
            ');

        $this->mailer->send($email);
        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}