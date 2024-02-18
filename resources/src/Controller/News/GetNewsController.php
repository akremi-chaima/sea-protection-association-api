<?php
namespace App\Controller\News;

use App\Manager\NewsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class GetNewsController extends AbstractController
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
     * Get news list
     *
     * @Route("/api/news", methods={"GET"})
     *
     * @OA\Tag(name="News")
     *
     * @OA\Response(response=200, description="News list")
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $news = $this->newsManager->findBy([], ['createdAt' => 'DESC']);
        $normalizedList = $this->serializer->serialize($news, 'json');
        return new JsonResponse(json_decode($normalizedList, true), Response::HTTP_OK);
    }
}