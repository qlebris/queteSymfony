<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 12/11/18
 * Time: 16:24
 */

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */

    public function index()
    {
    return $this->render("home.html.twig");
    }

}