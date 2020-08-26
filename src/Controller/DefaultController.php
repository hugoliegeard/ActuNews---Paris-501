<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
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
        # 1. Récupérer les données dans la BDD (Model)
        # Récupération des 6 derniers articles de la base.
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ['id' => 'DESC'], 6);

        # 2. Transmettre à la vue les données
        return $this->render('default/home.html.twig', [
            'posts' => $posts
        ]);
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
     * @param Category $category
     * @param $alias
     * @return Response
     */
    public function category(Category $category, $alias)
    {
        # Récupération de la catégorie

        # METHODE I
        #$category = $this->getDoctrine()
        #    ->getRepository(Category::class)
        #    ->findOneBy(['alias' => $alias]);

        # METHODE II
        #$category = $this->getDoctrine()
        #    ->getRepository(Category::class)
        #    ->findOneByAlias($alias);

        # Transmission a la vue
        return $this->render('default/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * Affiche un article en particulier
     * @Route("/{category}/{alias}_{id}.html",
     *     name="default_article",
     *     methods={"GET"})
     * @param Post|null $post
     * @param $id
     * @return Response
     * https://localhost:8000/sante/une-deuxieme-vague-ou-pas_15678.html
     */
    public function article($id, Post $post = null)
    {

        # Methode I ou II
        #$post = $this->getDoctrine()
        #    ->getRepository(Post::class)
        #    ->find($id);

        # Si aucun article trouvé, redirection page accueil
        if($post === null) {
            return $this->redirectToRoute('home');
        }

        # Transmission de l'article a la vue.
        return $this->render('default/article.html.twig', [
            'post' => $post
        ]);
    }

}
