<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/membre")
 */
class UserController extends AbstractController
{
    /**
     * Page Inscription
     * @Route("/inscription.html", name="user_register", methods={"GET|POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        # Création d'un nouvel utilisateur
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTime());

        # $user->setFirstname('Hugo');
        # $user->setLastname('LIEGEARD');

        # Création du Formulaire de cet utilisateur
        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, [
                'label' => 'Prénom.'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom.'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email.'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de Passe.'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris !",
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
            ->getForm();

        # Traitement de la Request. Permet a SF de récupérer les données soumise
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Encoder le mot de passe
            $user->setPassword(
              $encoder->encodePassword($user, $user->getPassword())
            );

            # Enregistrer en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # Notification Flash
            $this->addFlash('notice',
                'Félicitation, vous pouvez maintenant vous connecter');

            # Redirection
            return $this->redirectToRoute('home');

        } // if form submitted

        # Transmission du formulaire à la vue
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}