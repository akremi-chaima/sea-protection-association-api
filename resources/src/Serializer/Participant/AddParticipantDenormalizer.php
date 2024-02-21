<?php
namespace App\Serializer\Participant;

use App\DTO\Participant\AddParticipantDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AddParticipantDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new AddParticipantDTO())
            ->setEmail($data['email'] ?? null)
            ->setPhoneNumber($data['phoneNumber'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setEventId($data['eventId'] ?? null)
            ->setLastName($data['lastName'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === AddParticipantDTO::class;
    }
}