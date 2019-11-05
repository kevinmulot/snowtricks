<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\UserEditFormType;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
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
    private $ema;

    /**
     * UserController constructor.
     * @param ProfileRepository $profileRepository
     * @param ObjectManager $ema
     */
    public function __construct(ProfileRepository $profileRepository, ObjectManager $ema)
    {
        $this->profileRepository = $profileRepository;
        $this->ema = $ema;
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $users = $this->getDoctrine()->getRepository(user::class)->findBy([], ['username' => 'ASC']);

        return $this->render('/user/manage.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/{username}", name="user")
     * @param User $user
     */
    public function profileView(User $user)
    {
        // usually you'll want to make sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        $profile = $user->getProfile();

        return $this->render('user/index.html.twig', array('profile' => $profile));
    }

    /**
     * @Route("/profile/{id}", name="profile_edit")
     * @param Profile $profile
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProfile(Request $request, profile $profile, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $fileUploader->removeUserPicture($profile->getImageName());
        $form = $this->createForm(profileFormType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->ema->flush();

            $this->addFlash('success', 'Profile picture updated !');
            return $this->profileView($profile->getUser());
        }
        return $this->render('user/profile.html.twig', [
            'profileForm' => $form->createView()]);
    }

    /**
     * @Route("/user/edit/{username}", name="user_edit")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, user $user)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ema->flush();
            $this->addFlash('success', 'Profile updated !');
            return $this->redirectToRoute('user', array('username' => $user->getUsername()));
        }
        return $this->render('user/edit.html.twig', [
            'edituserForm' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/delete/{username}", name="user_delete")
     * @param User $user
     */
    public function deleteUser(User $user, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $profile = $user->getProfile();
        $fileName = $profile->getImageName();
        $fileUploader->removeUserPicture($fileName);
        $this->ema->remove($user);
        $this->ema->flush();

        return $this->index();
    }

    /**
     * @Route("/user/delete/{username}", name="account_delete")
     * @param User $user
     */
    public function deleteAccount(User $user, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $profile = $user->getProfile();
        $fileName = $profile->getImageName();
        $fileUploader->removeUserPicture($fileName);
        $this->get('security.token_storage')->setToken(null);
        $this->ema->remove($user);
        $this->ema->flush();

        return $this->redirect('/');
    }
}
