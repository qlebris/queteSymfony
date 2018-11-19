<?php
/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 12/11/18
 * Time: 17:07
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Tag;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/categories", name="category_index")
     */
    public function indexCategory()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'No category found in category\'s table.'
            );
        }

        return $this->render(
            'blog/categories.html.twig',
            ['categories' => $categories]
        );
    }

    /**
     * @Route("/category", name="category_add")
     */

    public function addCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category    );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
        }


        return $this->render('/blog/addCategory.html.twig',
            ['form' => $form->createView()]
        );

    }

    /**
     * @Route("/category/{category}", name="blog_show_category")
     */
    public function showByCategory(string $category): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($category);

        $articles = $category->getArticles();


        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in category\'s table.'
            );
        }

        return $this->render(
            'blog/category.html.twig',
            ['articles' => $articles,
                'category' => $category]
        );
    }

    /**
     * @Route("/tag/{tag}", name="blog_show_tag")
     * @return Response
     */

    public function showByTag(string $tag): Response
    {
        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findOneBy(['name' => mb_strtolower($tag)]);

        $articles = $tag->getArticles();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in tag\'s table.'
            );
        }

        return $this->render(
            'blog/tag.html.twig',
            ['articles' => $articles,
                'tag' => $tag]
        );

    }

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
        $articleCategory = $article->getCategory();
        $tags = $article->getTags();

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $slug . ' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'category' => $articleCategory,
                'tags' => $tags,
                'slug' => $slug,
            ]
        );
    }
}