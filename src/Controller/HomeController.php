<?php

namespace App\Controller;

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
    private $ema;

    /**
     * UserController constructor.
     * @param TrickRepository $Repository
     * @param ObjectManager $ema
     */
    public function __construct(TrickRepository $Repository, ObjectManager $ema)
    {
        $this->trickRepository = $Repository;
        $this->ema = $ema;
    }

    /**
     *
     * @route("/", name="home")
     */
    public function index()
    {
        $trick = $this->trickRepository->findBy([], ['datePost' => 'ASC']);

        return $this->render('/pages/home.html.twig', ['trick' => $trick]);
    }
}
