<?php
namespace App\Controller\User;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ResetPasswordController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var MailerInterface */
    private $mailer;

    /** @var UserPasswordHasherInterface */
    private $userPasswordHasherInterface;

    /**
     * @param UserManager $userManager
     * @param MailerInterface $mailer
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function __construct(
        UserManager $userManager,
        MailerInterface $mailer,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    /**
     * Reset password (send new password by email)
     *
     * @Route("/api/reset/password/{email}", methods={"GET"})
     *
     * @OA\Tag(name="Users")
     *
     * @OA\Response(response=200, description="New password sent by email")
     * @OA\Response(response=400, description="The user was not found")
     *
     * @param string $email
     * @return JsonResponse
     */
    public function __invoke(string $email): JsonResponse
    {
        // Get active user by email
        /** @var User|null $user */
        $user = $this->userManager->findOneBy(['email' => $email, 'active' => true]);
        if (empty($user)) {
            return new JsonResponse(['error_message' => 'The user was not found'], Response::HTTP_BAD_REQUEST);
        }

        $password = $this->randomPassword();

        // reset user password
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, hash('sha256', $password)));
        $this->userManager->save($user);

        // send new password by email
        $emailObj = (new Email())
            ->to($email)
            ->subject('Nouveau mot de passe')
            ->from(getenv('CONTACT_MAIL'))
            ->html('
                <div>Votre nouveau mot de passe: '.$password.'</div>
            ');

        $this->mailer->send($emailObj);
        return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
    }

    /**
     * Generate random password
     * @return string
     */
    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}