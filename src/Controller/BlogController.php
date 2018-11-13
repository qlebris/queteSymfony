<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 12/11/18
 * Time: 17:07
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
        /**
         * @Route("/blog/{slug}", requirements={"slug"="[a-z0-9\-]+"}, name="blog_show")
         */

    public function show(string $slug = 'article-sans-nom')
    {
        $page = ucwords(str_replace('-', ' ', $slug));

        return $this->render('blog/show.html.twig', ['page' => $page]);

    }
}