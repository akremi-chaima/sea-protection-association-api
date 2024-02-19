<?php
namespace App\Controller\News;

use App\DTO\News\CreateNewsDTO;
use App\Entity\News;
use App\Manager\NewsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateNewsController extends AbstractController
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
     * Create news
     *
     * @Route("/api/news", methods={"POST"})
     *
     * @OA\Tag(name="News")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"title", "description"},
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="file", type="file"),
     *          )
     *      )
     * )
     *
     * @OA\Response(response=200, description="News created")
     * @OA\Response(response=400, description="Invalid data")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        /** @var CreateNewsDTO $dto */
        $dto = $this->serializer->deserialize(json_encode($request->request->all()), CreateNewsDTO::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            $display = [];
            foreach ($errors as $error) {
                $display[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['error_messages' => $display], Response::HTTP_BAD_REQUEST);
        }

        $news = (new News())
            ->setTitle($dto->getTitle())
            ->setDescription($dto->getDescription())
            ->setCreatedAt(new \DateTime())
            ->setPicture(!is_null($file) ? $file->getClientOriginalName() : null);

        $this->newsManager->save($news);

        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}