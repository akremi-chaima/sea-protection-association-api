<?php
namespace App\Controller\News;

use App\Manager\NewsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetNewsByIdController extends AbstractController
{
    /** @var NewsManager */
    private $newsManager;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param NewsManager $newsManager
     * @param SerializerInterface $serializer
     */
    public function __construct(NewsManager $newsManager, SerializerInterface $serializer)
    {
        $this->newsManager = $newsManager;
        $this->serializer = $serializer;
    }

    /**
     * Get news by id
     *
     * @Route("/api/private/news/{newsId}", methods={"GET"})
     *
     * @OA\Tag(name="News")
     *
     * @OA\Response(response=200, description="News details")
     * @OA\Response(response=400, description="News was not found")
     *
     * @param int $newsId
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(int $newsId, UserInterface $user): JsonResponse
    {
        $news = $this->newsManager->findOneBy(['id' => $newsId]);
        if (is_null($news)) {
            return new JsonResponse(['error_message' => 'News was not found'], Response::HTTP_BAD_REQUEST);
        }

        $normalizedList = $this->serializer->serialize($news, 'json');
        return new JsonResponse(json_decode($normalizedList, true), Response::HTTP_OK);
    }
}