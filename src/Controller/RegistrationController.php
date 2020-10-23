<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\RegistrationFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $em;

    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/register", name="registration")
     */
    public function index(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(RegistrationFormType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime();
            $account->setCreatedAt($now);
            $owner = $account->getOwner();
            $owner->setPassword($this->encoder->encodePassword($owner, $owner->getPassword()));
            $account->getOwner()->setRoles(['ROLE_OWNER']);
            $account->addUser($owner);

            try {
                $this->em->persist($account);
                $this->em->flush();
            } catch (UniqueConstraintViolationException $e) {
                $form->get('owner')->get('email')->addError(new FormError('You are already registered. Please login.'));

                return $this->render('registration/index.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $this->addFlash('success', 'Registration successful. Please check your email for login instructions.');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
