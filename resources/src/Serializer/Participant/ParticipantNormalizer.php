<?php
namespace App\Serializer\Participant;

use App\Entity\Participant;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ParticipantNormalizer implements NormalizerInterface
{
    /**
     * @param Participant $participant
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($participant, string $format = null, array $context = [])
    {
        return [
            'id' => $participant->getId(),
            'email' => $participant->getEmail(),
            'phoneNumber' => $participant->getPhoneNumber(),
            'firstName' => $participant->getFirstName(),
            'lastName' => $participant->getLastName(),
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Participant;
    }
}