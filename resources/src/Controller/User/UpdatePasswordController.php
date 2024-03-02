<?php
namespace App\Controller\User;

use App\DTO\User\UpdatePasswordDTO;
use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdatePasswordController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var UserPasswordHasherInterface */
    private $userPasswordHasherInterface;

    /**
     * @param UserManager $userManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function __construct(
        UserManager $userManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
        $this->userManager = $userManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Update user password
     *
     * @Route("/api/private/update/password", methods={"PUT"})
     *
     * @OA\Tag(name="Users")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              required={"password", "newPassword", "newPasswordConfirmation"},
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="newPassword", type="string"),
     *              @OA\Property(property="newPasswordConfirmation", type="string"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="Password updated")
     * @OA\Response(response=400, description="The account is disabled | Invalid password | The newPasswordConfirmation and newPassword should have the same value")
     *
     * @param Request $request
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserInterface $user): JsonResponse
    {
        /** @var UpdatePasswordDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), UpdatePasswordDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        if ($dto->getNewPassword() !== $dto->getNewPasswordConfirmation()) {
            return new JsonResponse(['error_message' => 'The newPasswordConfirmation and newPassword should have the same value'], Response::HTTP_BAD_REQUEST);
        }

        // Get connected user
        /** @var User|null $userById */
        $userById = $this->userManager->findOneBy(['id' => $user->getId(), 'active' => true]);
        if (is_null($userById)) {
            return new JsonResponse(['error_message' => 'The account is disabled'], Response::HTTP_BAD_REQUEST);
        }

        // check if the password is equal to user password
        if (!$this->userPasswordHasherInterface->isPasswordValid($userById, hash('sha256', $dto->getPassword()))) {
            return new JsonResponse(['error_message' => 'Invalid password'], Response::HTTP_BAD_REQUEST);
        }

        $userById->setPassword($this->userPasswordHasherInterface->hashPassword($userById, hash('sha256', $dto->getNewPassword())));
        $this->userManager->save($userById);
        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}