<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * TrickController constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/tricks", name="tricks")
     */
    public function index()
    {
        return $this->render('/trick/tricks.html.twig');
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
        $pictures = $trick->getPicture();
        $videos = $trick->getVideo();
        return $this->render('trick/trick.view.html.twig', ['trick' => $trick, 'commentForm' => $form->createView(), 'comments' => $comments, 'pictures' => $pictures, 'videos' => $videos]);
    }

    /**
     * @Route("/tricks/delete/{id}", name="trick_delete")
     * @param Trick $trick
     */
    public function deleteTrick(Trick $trick, FileUploader $fileUploader)
    {
        $pictures = $trick->getPicture();
        foreach ($pictures as $picture) {
            /*@object Picture $picture */
            $name = $picture->getName();
            $fileUploader->remove($name);
        }
        $this->em->remove($trick);
        $this->em->flush();
        return $this->redirect('/');
    }

    /**
     * @Route("/trick/edit/{id}", name="trick_edit")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, trick $trick)
    {   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $trickForm = $this->createForm(TrickFormType::class, $trick);
        $trickForm->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $trick->setDateUpdate(new \DateTime('now'));
            $this->em->flush();
            return $this->redirectToRoute('trick_view', array('id' => $trick->getId()));
        }
        $pictures = $trick->getPicture();
        $videos = $trick->getVideo();
        return $this->render('trick/trick.edit.html.twig', [
            'trickForm' => $trickForm->createView(),
            'trick' => $trick, 'pictures' => $pictures, 'videos' => $videos]);
    }
}
