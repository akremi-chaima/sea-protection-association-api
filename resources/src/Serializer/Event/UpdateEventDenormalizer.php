<?php
namespace App\Serializer\Event;

use App\DTO\Event\UpdateEventDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UpdateEventDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new UpdateEventDTO())
            ->setId($data['id'] ?? null)
            ->setTitle($data['title'] ?? null)
            ->setAddress($data['address'] ?? null)
            ->setDate($data['date'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === UpdateEventDTO::class;
    }
}