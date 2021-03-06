<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgottenPasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws \Exception
     */
    public function logout()
    {
        $this->addFlash('notice', 'You have been diconnected');
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $userInfo = ['username' => null];
        $form = $this->createForm(ForgottenPasswordFormType::class, $userInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $userInfo = $form->getData();
            $username = $userInfo['username'];
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Unknown Username');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('home');
            }
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('noreply@keimuo.com')
                ->setTo($user->getEmail())
                ->setBody("Reset password link: " . $url, 'text/html');
            $mailer->send($message);
            $this->addFlash('notice', 'An Mail have been sent to the email address you provided.');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/forgotten_password.html.twig', [
            'forgottenPasswordForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $username = $form->get('username')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
            /* @var $user User */
            if ($user === null or $username != $user->getUsername()) {
                $this->addFlash('danger', 'Unknown User');

                return $this->redirectToRoute('home');
            }
            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $plainPassword));
            $entityManager->flush();
            $this->addFlash('notice', 'Password updated !');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password.html.twig', ['token' => $token,
            'resetPasswordForm' => $form->createView()]);
    }
}
