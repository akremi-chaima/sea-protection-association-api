<?php
namespace App\Serializer\News;

use App\DTO\News\UpdateNewsDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UpdateNewsDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new UpdateNewsDTO())
            ->setId($data['id'] ? intval($data['id']) : null)
            ->setTitle($data['title'] ?? null)
            ->setDescription($data['description'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === UpdateNewsDTO::class;
    }
}