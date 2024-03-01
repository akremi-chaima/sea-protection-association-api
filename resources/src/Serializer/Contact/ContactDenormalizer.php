<?php
namespace App\Serializer\Contact;

use App\DTO\Contact\ContactDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ContactDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new ContactDTO())
            ->setEmail($data['email'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setMessage($data['message'] ?? null)
            ->setPhoneNumber($data['phoneNumber'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ContactDTO::class;
    }
}