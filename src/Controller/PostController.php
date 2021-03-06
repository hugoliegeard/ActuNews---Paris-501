<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/article")
 */
class PostController extends AbstractController
{

    /**
     * Formulaire pour rédiger un article
     * @IsGranted("ROLE_JOURNALIST")
     * @Route("/nouveau", name="post_new", methods={"GET|POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function newPost(Request $request, SluggerInterface $slugger)
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
                    'placeholder' => 'Titre de l\'article'
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
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
            ->getForm();

        # Permet à Symfony de traiter les données reçus.
        $form->handleRequest($request);

        # Vérifier si le formulaire est soumis
        # Vérifier si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            # Génération de l'Alias
            $post->setAlias(
                $slugger->slug(
                    $post->getTitle()
                )
            );

            # ---------------------- Upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            $newFilename = $post->getAlias() . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('posts_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                # TODO Handle catch exception
            }

            $post->setImage($newFilename);
            # ---------------------- Upload de l'image

            # Enclencher la sauvegarde des données
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            # Notification Flash qui se base sur les sessions.
            $this->addFlash('notice',
                'Félicitation votre article est en ligne !');

            # Redirection
            return $this->redirectToRoute('default_article', [
                'category' => $post->getCategory()->getAlias(),
                'alias' => $post->getAlias(),
                'id' => $post->getId()
            ]);
        }

        # Transmission du formulaire à la vue
        return $this->render('post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}