<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 22/11/18
 * Time: 10:50
 */

namespace App\Controller;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @param Category $category
     * @Route("/show/category/{id}", name = "category_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function show(Category $category)
    {
        return $this->render('/blog/showCategory.html.twig', compact('category'));
    }
}
