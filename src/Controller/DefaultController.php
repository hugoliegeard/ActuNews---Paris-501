<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'accueil, liste les derniers articles
     */
    public function home()
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * Page de Contact
     */
    public function contact()
    {
        return new Response("<h1>Page Contact</h1>");
    }

    /**
     * Affiche les articles d'une catégorie
     * @Route("/categorie/{alias}",
     *     name="default_category",
     *     methods={"GET"})
     * @param $alias
     * @return Response
     */
    public function category($alias)
    {
        return $this->render('default/category.html.twig');
    }

    /**
     * Affiche un article en particulier
     * @Route("/{category}/{alias}_{id}.html",
     *     name="default_article",
     *     methods={"GET"})
     * @param $id
     * @param $category
     * @param $alias
     * @return Response
     * https://localhost:8000/sante/une-deuxieme-vague-ou-pas_15678.html
     */
    public function article($id, $category, $alias)
    {
        return $this->render('default/article.html.twig');
    }

}
