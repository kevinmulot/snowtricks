<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use App\Repository\TrickRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * UserController constructor.
     * @param TrickRepository $Repository
     * @param ObjectManager $em
     */
    public function __construct( TrickRepository $Repository, ObjectManager $em)
    {
        $this->trickRepository = $Repository;
        $this->em = $em;
    }

    /**
     *
     * @route("/", name="home")
     */
    public function index()
    {
        $trick = $this->trickRepository->findBy([], ['datePost'=> 'ASC']);
        return $this->render('/pages/home.html.twig', ['trick'=> $trick]);
    }
}
