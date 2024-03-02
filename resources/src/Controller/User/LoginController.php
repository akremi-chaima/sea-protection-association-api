<?php
namespace App\Controller\User;

use App\DTO\User\LoginDTO;
use App\Entity\User;
use App\Manager\UserManager;
use App\Security\JwtUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class LoginController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var UserPasswordHasherInterface */
    private $userPasswordHasherInterface;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var JwtUtil */
    protected $jwtUtil;

    /**
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param JwtUtil $jwtUtil
     */
    public function __construct(
        UserManager $userManager,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JwtUtil $jwtUtil
    ) {
        $this->userManager = $userManager;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->jwtUtil = $jwtUtil;
    }

    /**
     * Login
     *
     * @Route("/api/login", methods={"POST"})
     *
     * @OA\Tag(name="Login")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="User login")
     * @OA\Response(response=400, description="Invalid credentials")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var LoginDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), LoginDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        // check used email
        $user = $this->userManager->findOneBy(['email' => $dto->getEmail()]);
        if (!$user instanceof User || !$this->userPasswordHasherInterface->isPasswordValid($user, hash('sha256', $dto->getPassword()))) {
            return new JsonResponse(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }


        $createdAt = new \DateTime();
        $expiresAt = (new \DateTime())->modify(getenv('TOKEN_EXPIRATION').' day');
        $tokenData = [
            'created_at' => $createdAt,
            'expires_at' => $expiresAt,
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getEmail()
            ],
        ];
        return new JsonResponse(
            [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'role' => $user->getRoles()[0],
                'expiration' => $expiresAt->format('Ymd'),
                'token' => $this->jwtUtil->encode($tokenData)
            ],
            Response::HTTP_OK
        );
    }
}