<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     *
     * @route("/", name="home")
     */
    public function index()
    {
        return $this->render('/pages/home.html.twig');
    }
}
