<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
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
    private $ema;

    /**
     * TrickController constructor.
     * @param ObjectManager $ema
     */
    public function __construct(ObjectManager $ema)
    {
        $this->ema = $ema;
    }

    /**
     * @Route("/admin/tricks/create", name="trick_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setDatePost(new \DateTime('now'));
            $this->ema->persist($trick);
            $this->ema->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('trick/create.html.twig', [
            'trickForm' => $form->createView()]);
    }

    /**
     * @Route("/tricks/view/{slug}", name="trick_view")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function trickView(Request $request, Trick $trick)
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment();
            $comment->setContent($form->get('content')->getData());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $this->ema->persist($comment);
            $this->ema->flush();

            return $this->redirectToRoute('trick_view', ['slug' => $trick->getSlug()]);
        }
        $comments = $trick->getComment();
        $pictures = $trick->getPicture();
        $videos = $trick->getVideo();

        return $this->render('trick/view.html.twig', ['trick' => $trick, 'commentForm' => $form->createView(), 'comments' => $comments, 'pictures' => $pictures, 'videos' => $videos]);
    }

    /**
     * @Route("/admin/tricks/delete/{slug}", name="trick_delete")
     * @param Trick $trick
     */
    public function deleteTrick(Trick $trick, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $pictures = $trick->getPicture();
        foreach ($pictures as $picture) {
            /*@object Picture $picture */
            $name = $picture->getName();
            $fileUploader->remove($name);
        }
        $this->ema->remove($trick);
        $this->ema->flush();

        return $this->redirect('/');
    }

    /**
     * @Route("/admin/trick/edit/{slug}", name="trick_edit")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, trick $trick)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $trickForm = $this->createForm(TrickFormType::class, $trick);
        $trickForm->handleRequest($request);

        if ($trickForm->isSubmitted() && $trickForm->isValid()) {
            $trick->setDateUpdate(new \DateTime('now'));
            $this->ema->flush();

            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
        }
        $pictures = $trick->getPicture();
        $videos = $trick->getVideo();

        return $this->render('trick/edit.html.twig', [
            'trickForm' => $trickForm->createView(),
            'trick' => $trick, 'pictures' => $pictures, 'videos' => $videos]);
    }
}
