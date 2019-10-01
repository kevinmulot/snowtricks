<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Profile;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickController
 * @package App\Controller
 */
class TrickController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @Route("/tricks", name="tricks")
     */
    public function index()
    {
        return $this->render('/trick/tricks.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

    /**
     * TrickController constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/tricks/create", name="trick_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setDatePost(new \DateTime('now'));
            $this->em->persist($trick);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('trick/create.html.twig', [
            'trickForm' => $form->createView()]);
    }

    /**
     * @Route("/tricks/view/{id}", name="trick_view")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function trickView(Request $request, Trick $trick)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $this->em->persist($comment);
            $this->em->flush();
        }
        $comments = $trick->getComment();
        return $this->render('trick/trick.view.html.twig', ['trick' => $trick, 'commentForm' => $form->createView(), 'comments' => $comments]);
    }
}
