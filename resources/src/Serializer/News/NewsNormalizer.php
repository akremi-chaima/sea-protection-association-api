<?php
namespace App\Serializer\News;

use App\Entity\News;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NewsNormalizer implements NormalizerInterface
{
    /**
     * @param News $news
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($news, string $format = null, array $context = [])
    {
        return [
            'id' => $news->getId(),
            'description' => $news->getDescription(),
            'title' => $news->getTitle(),
            'picture' => $news->getPicture(),
            'createdAt' => $news->getCreatedAt()->format('d/m/Y'),
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof News;
    }
}