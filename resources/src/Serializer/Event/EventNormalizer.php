<?php
namespace App\Serializer\Event;

use App\Entity\Event;
use App\Manager\ParticipantManager;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EventNormalizer implements NormalizerInterface
{
    private ParticipantManager $participantManager;

    /**
     * @param ParticipantManager $participantManager
     */
    public function __construct(ParticipantManager $participantManager)
    {
        $this->participantManager = $participantManager;
    }

    /**
     * @param Event $event
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($event, string $format = null, array $context = [])
    {
        return [
            'id' => $event->getId(),
            'address' => $event->getAddress(),
            'title' => $event->getTitle(),
            'date' => $event->getDate()->format('d/m/Y H:i'),
            'participants' => $this->participantManager->count($event)
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Event;
    }
}