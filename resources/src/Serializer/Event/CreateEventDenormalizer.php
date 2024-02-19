<?php
namespace App\Serializer\Event;

use App\DTO\Event\CreateEventDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CreateEventDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new CreateEventDTO())
            ->setTitle($data['title'] ?? null)
            ->setAddress($data['address'] ?? null)
            ->setDate($data['date'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === CreateEventDTO::class;
    }
}