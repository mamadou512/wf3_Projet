<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserToken;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Service\NotifyService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        NotifyService $notifyService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);

            // Create UserToken
            $userToken = (new UserToken())
                ->setUser($user)
                ->setExpiresAt(new DateTime("+ 10 year"));
            $entityManager->persist($userToken);

            $entityManager->flush();

            // do anything else you need here, like send an email
            $email = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject("Validez votre compte")
                ->htmlTemplate('registration/email/register_verification_email.email.twig')
                ->context([
                    'user' => $user,
                    'link' => $this->generateUrl(
                        'register_verification_email',
                        ['token' => $userToken->getToken()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                ]);
            $notifyService->sendEmail($email);

            return $this->redirectToRoute('register_confirmation');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/inscription/confirmation', name: 'register_confirmation')]
    public function registerConfirmation(): Response
    {
        return $this->render('registration/confirmation.html.twig');
    }

    #[Route('/inscription/verification-email/{token}', name: 'register_verification_email')]
    public function registerVerificationEmail(
       ?UserToken $userToken,
       EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator,
        Request $request
    ): Response
    {
        if ($userToken && $userToken->isValid()) {
            $userToken->setUsedAt(new DateTimeImmutable());
            $user = $userToken->getUser();
            $user->setStatus(User::STATUS_ACTIVE);

            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        $this->addFlash('danger', "Token invalide, Veuillez recréer un compte.");
        return $this->redirectToRoute('app_register');
    }
}
