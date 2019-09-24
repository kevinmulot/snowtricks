<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\UserEditFormType;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * @var ProfileRepository
     */
    private $profileRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * UserController constructor.
     * @param ProfileRepository $profileRepository
     * @param ObjectManager $em
     */
    public function __construct( ProfileRepository $profileRepository, ObjectManager $em)
    {
        $this->profileRepository = $profileRepository;
        $this->em = $em;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request)
    {
        // usually you'll want to make sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class

        $profile = $this->profileRepository->findOneBy(['user' => $this->getUser()->getId()]);
        return $this->render('user/index.html.twig', array('profile' => $profile) );
    }

    /**
     * @Route("/profile/{id}", name="profile_edit")
     * @param Profile $profile
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProfile(Request $request, profile $profile)
    {
        $form = $this->createForm(profileFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('user');
        }
        return $this->render('user/profile.html.twig', [
            'profileForm' => $form->createView()]);
    }

    /**
     * @Route("/user/{id}", name="user_edit")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, user $user)
    {
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('user');
        }
        return $this->render('user/edit.html.twig', [
            'edituserForm' => $form->createView()]);
    }
}
