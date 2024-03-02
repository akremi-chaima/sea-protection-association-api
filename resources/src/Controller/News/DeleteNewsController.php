<?php
namespace App\Controller\News;

use App\Manager\NewsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteNewsController extends AbstractController
{
    /** @var NewsManager */
    private $newsManager;

    /**
     * @param NewsManager $newsManager
     */
    public function __construct(NewsManager $newsManager)
    {
        $this->newsManager = $newsManager;
    }

    /**
     * Delete news
     *
     * @Route("/api/private/news/{newsId}", methods={"DELETE"})
     *
     * @OA\Tag(name="News")
     *
     * @OA\Response(response=200, description="News deleted")
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

        $this->newsManager->delete($news);

        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }
}