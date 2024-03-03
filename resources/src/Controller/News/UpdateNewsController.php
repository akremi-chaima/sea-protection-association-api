<?php
namespace App\Controller\News;

use App\DTO\News\UpdateNewsDTO;
use App\Manager\NewsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateNewsController extends AbstractController
{
    /** @var NewsManager */
    private $newsManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param NewsManager $newsManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        NewsManager $newsManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->newsManager = $newsManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Update news
     *
     * @Route("/api/private/update/news", methods={"POST"})
     *
     * @OA\Tag(name="News")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"id", "title", "description"},
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="file", type="file"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="News updated")
     * @OA\Response(response=400, description="Invalid data")
     *
     * @param Request $request
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserInterface $user): JsonResponse
    {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        /** @var UpdateNewsDTO $dto */
        $dto = $this->serializer->deserialize(json_encode($request->request->all()), UpdateNewsDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        $news = $this->newsManager->findOneBy(['id' => $dto->getId()]);
        if (is_null($news)) {
            return new JsonResponse(['error_message' => 'News was not found'], Response::HTTP_BAD_REQUEST);
        }

        $news->setTitle($dto->getTitle())
            ->setDescription($dto->getDescription());

        if (!empty($file)) {
            $news->setPicture($file->getClientOriginalName());
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/'.$news->getId();
            $file->move($destination, $file->getClientOriginalName());
        }

        $this->newsManager->save($news);

        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}