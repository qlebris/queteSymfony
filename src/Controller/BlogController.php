<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 12/11/18
 * Time: 17:07
 */

namespace App\Controller;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{slug<^[a-z0-9-]+$>}",
     * defaults={"slug" = null},
     * name="blog_show")
     * @return Response A response instance
     */

    public function show($slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $slug . ' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="blog_index")
     */

    public function index()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @Route("/category/{category}", name="blog_show_category")
     */
    public function showByCategory(string $category): Response
    {
        $idCategory = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $category])
            ->getId();

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['category' => $idCategory],['id' => 'desc'],3);


        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in category\'s table.'
            );
        }

        return $this->render(
            'blog/category.html.twig',
            ['articles' => $articles,
                'name' => $category]
        );
    }
}