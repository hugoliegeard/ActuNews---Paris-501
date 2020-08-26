<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/article")
 */
class PostController extends AbstractController
{

    /**
     * Formulaire pour rédiger un article
     * @Route("/nouveau", name="post_new", methods={"GET|POST"})
     */
    public function newPost()
    {

        # Créer un nouvel objet Post
        $post = new Post();

        # Récupération d'un User dans la BDD
        # TODO : Remplacer par l'utilisateur connecté en session.
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(3);

        # Affectation du User à l'article
        $post->setUser($user);

        # Création du Formulaire
        $form = $this->createFormBuilder($post)

            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'TItre de l\'article'
                ]
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => false
            ])

            ->add('content', TextareaType::class, [
                'label' => false
            ])

            ->add('image', FileType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'dropify'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon article',
            ])

            ->getForm()

        ;

        # Transmission du formulaire à la vue
        return $this->render('post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}