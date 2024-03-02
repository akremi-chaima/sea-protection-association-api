<?php
namespace App\Serializer\User;

use App\DTO\User\UpdatePasswordDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UpdatePasswordDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return (new UpdatePasswordDTO())
            ->setPassword($data['password'] ?? null)
            ->setNewPasswordConfirmation($data['newPasswordConfirmation'] ?? null)
            ->setNewPassword($data['newPassword'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === UpdatePasswordDTO::class;
    }
}