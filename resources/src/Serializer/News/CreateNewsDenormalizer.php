<?php
namespace App\Serializer\News;

use App\DTO\News\CreateNewsDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CreateNewsDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new CreateNewsDTO())
            ->setTitle($data['title'] ?? null)
            ->setDescription($data['description'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === CreateNewsDTO::class;
    }
}