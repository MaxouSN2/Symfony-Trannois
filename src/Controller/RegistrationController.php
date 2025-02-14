<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = [
            'email' => '',
            'name' => '',
            'password' => '',
            'confirmPassword' => '',
            'plan' => '1Mo', // Valeur par défaut
        ];

        // Création du formulaire avec un champ pour la formule
        $form = $this->createFormBuilder($data, [
                'csrf_protection' => true, // Protection CSRF activée
            ])
            ->add('email', EmailType::class, [
                'label' => $translator->trans('E-mail'),
                'constraints' => [
                    new NotBlank(['message' => $translator->trans('Veuillez saisir un e-mail.')]),
                ]
            ])
            ->add('name', TextType::class, [
                'label' => $translator->trans('Nom'),
                'constraints' => [
                    new NotBlank(['message' => $translator->trans('Veuillez saisir un nom.')]),
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => $translator->trans('Mot de passe'),
                'constraints' => [
                    new NotBlank(['message' => $translator->trans('Veuillez saisir un mot de passe.')]),
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => $translator->trans('Confirmer le mot de passe'),
                'constraints' => [
                    new NotBlank(['message' => $translator->trans('Veuillez confirmer votre mot de passe.')]),
                ]
            ])
            ->add('plan', ChoiceType::class, [
                'label' => $translator->trans('Choisir une formule'),
                'choices' => [
                    '1 Mo' => '1Mo',
                    '10 Mo' => '10Mo',
                    '100 Mo' => '100Mo',
                ],
                'constraints' => [
                    new NotBlank(['message' => $translator->trans('Veuillez choisir une formule.')]),
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => $translator->trans('S\'inscrire')])
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // Récupérer les données soumises

            // Vérifier que les mots de passe correspondent
            if ($data['password'] !== $data['confirmPassword']) {
                $form->get('confirmPassword')->addError(new FormError($translator->trans('Les mots de passe ne correspondent pas')));
            }

            // Si le formulaire est valide et les mots de passe correspondent
            if ($form->isValid() && $data['password'] === $data['confirmPassword']) {
                // Création de l'utilisateur
                $user = new User();
                $user->setEmail($data['email']);
                $user->setName($data['name']);
                $user->setPlan($data['plan']);  // Enregistrement de la formule choisie

                // Hachage du mot de passe
                //$hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
               // $user->setPassword($hashedPassword);

                // Sauvegarde de l'utilisateur dans la base de données
                //$entityManager->persist($user);
                //$entityManager->flush();

                // Redirection vers la page de succès
                return $this->redirectToRoute('app_register_success', ['login' => $data['email']]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/success/{login}', name: 'app_register_success')]
    public function success(string $login): Response
    {
        return $this->render('registration/success.html.twig', [
            'login' => $login,
        ]);
    }
}
