<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends AbstractController
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var ObjectManager
     */
    private $ema;

    /**
     * UserController constructor.
     * @param CommentRepository $Repository
     * @param ObjectManager $ema
     */
    public function __construct(CommentRepository $Repository, ObjectManager $ema)
    {
        $this->commentRepository = $Repository;
        $this->ema = $ema;
    }

    /**
     * @Route("/comment/delete/{id}", name="comment_delete")
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteComment(Comment $comment)
    {
        $this->ema->remove($comment);
        $this->ema->flush();
        $trick = $comment->getTrick();
        $idy = $trick->getId();

        return $this->redirectToRoute('trick_view', array('id' => $idy));
    }
}
