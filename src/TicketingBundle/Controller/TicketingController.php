<?php

namespace TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TicketingBundle\Entity\Demandes;
use TicketingBundle\Entity\Tickets;
use TicketingUserBundle\Entity\Utilisateurs;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use TicketingUserBundle\TicketingUserBundle;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class TicketingController extends Controller
{
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('ticketing_admin');

        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            return $this->redirectToRoute('ticketing_user');

        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_TECH')) {

            return $this->redirectToRoute('ticketing_tech');

        }
        return $this->render('TicketingBundle:Ticketing:index.html.twig');
    }

    public function userAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('ticketing_admin');
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_TECH')) {

            return $this->redirectToRoute('ticketing_tech');
        }

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DEM')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException('Accès limité aux Utilisateurs.');
        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');


        $listDemandes = $repository->myFindOne($this->getUser());
        $listDemandesResolues = $repository->myFindOneres($this->getUser());
        return $this->render('TicketingBundle:Ticketing:user.html.twig', array('listDemandes' => $listDemandes, 'listDemandesResolues' => $listDemandesResolues));
    }

    public function techAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            return $this->redirectToRoute('ticketing_user');
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_RAPP')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException('Accès limité aux Techniciens.');

        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Tickets');


        $listTickets = $repository->myFindOnea($this->getUser());
        $listTicketsres = $repository->myFindOneres($this->getUser());
        return $this->render('TicketingBundle:Ticketing:tech.html.twig', array('listTickets' => $listTickets, 'listTicketsres' => $listTicketsres));
    }

    public function adminAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            return $this->redirectToRoute('ticketing_user');

        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_TRIE')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException("Accès limité a l'Administrateur.");
        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');


        $listDemandes = $repository->myFindOnead();
        return $this->render('TicketingBundle:Ticketing:admin.html.twig', array('listDemandes' => $listDemandes));


    }

    public function addAction(Request $request)
    {
        // On crée un objet Advert

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('ticketing_admin');

        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_TECH')) {

            return $this->redirectToRoute('ticketing_tech');

        }


        $demande = new Demandes();


        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $demande);


        // On ajoute les champs de l'entité que l'on veut à notre formulaire

        $formBuilder
            ->add('objet', TextType::class, array('label' => 'Objet :'))
            ->add('description', TextareaType::class, array('label' => 'Description :'))
            ->add('piecejointe', FileType::class, array('label' => 'Image à joindre :',
        'required'  => false, 'empty_data' => 'ok'))
            ->add('Envoyer', SubmitType::class, array('label' => 'Créer la demande'));

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
        // On récupère le service


// Si l'utilisateur courant est anonyme, $user vaut « anon. »


// Sinon, c'est une instance de notre entité User, on peut l'utiliser normalement

        $demande->setUtilisateur($this->getUser());

        // À partir du formBuilder, on génère le formulaire

        $form = $formBuilder->getForm();

        // Si la requête est en POST

        if ($request->isMethod('POST')) {

            // On fait le lien Requête <-> Formulaire

            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur

            $form->handleRequest($request);


            // On vérifie que les valeurs entrées sont correctes

            $demande1 = $demande;
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            if ($form->isValid()) {



                $someNewFilename = "piecejointe" . $demande->getObjet() . $demande->getDescription();
                $file = $form['piecejointe']->getData();
                if($file != 'ok') {

                    $file->move('Pieces', $someNewFilename);

                    $demande->setPiecejointe('Pieces/' . $someNewFilename);
                }

                // On enregistre notre objet $advert dans la base de données, par exemple

                $em = $this->getDoctrine()->getManager();

                $em->persist($demande);


                $em->flush();


                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ticketing_user');


            }

        }


        // On passe la méthode createView() du formulaire à la vue

        // afin qu'elle puisse afficher le formulaire toute seule

        return $this->render('TicketingBundle:Ticketing:add.html.twig', array(

            'form' => $form->createView(),

        ));
    }

    public function viewAction($page)

    {

        // Ici, on récupérera l'annonce correspondante à l'id $id


        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Tickets');

        $ticket = $repository->myFindOneticka($page);

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');

        $Listdem = $repository->myFindOnedem($page);


        return $this->render('TicketingBundle:Ticketing:view.html.twig', array(

            'page' => $page, 'ticket' => $ticket, 'Listdem' => $Listdem

        ));


    }

    public function demandesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_TRIE')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException("Accès limité a l'Administrateur.");
        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');


        $listDemandes = $repository->myFindOneaddems();
        return $this->render('TicketingBundle:Ticketing:demande.html.twig', array('listDemandes' => $listDemandes));


    }

    public function attributeAction(Request $request, $page)
    {
        $ticket = new Tickets();


        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $ticket);


        // On ajoute les champs de l'entité que l'on veut à notre formulaire

        $formBuilder
            ->add('delai', integerType::class, array('label' => 'Délai :'))
            ->add('urgence', CheckboxType::class, array('label' => 'Demande urgente ?', 'required'  => false))
            ->add('utilisateur', EntityType::class, array('label' => 'Technicien à attribuer',
                'class' => Utilisateurs::class, 'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where("u.roles != 'a:1:{i:0;s:9:\"ROLE_USER\";}'");

                }, 'choice_label' => 'username',))
            ->add('Envoyer', SubmitType::class, array('label' => 'Attribuer'));

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
        // On récupère le service


// Si l'utilisateur courant est anonyme, $user vaut « anon. »


// Sinon, c'est une instance de notre entité User, on peut l'utiliser normalement

        // À partir du formBuilder, on génère le formulaire
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');


        $demandes = $repository->myFindOnedem($page);
        foreach ($demandes as $demande) {
            $ticket->setDemande($demande);
            $demande->setEtat('attribue');
        }


        $formticket = $formBuilder->getForm();

        // Si la requête est en POST

        if ($request->isMethod('POST')) {

            // On fait le lien Requête <-> Formulaire

            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur

            $formticket->handleRequest($request);


            // On vérifie que les valeurs entrées sont correctes

            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            if ($formticket->isValid()) {

                // On enregistre notre objet $advert dans la base de données, par exemple

                $em = $this->getDoctrine()->getManager();

                $em->persist($ticket);

                $em->flush();


                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ticketing_admin');
            }

        }
        return $this->render('TicketingBundle:Ticketing:attribute.html.twig', array(

            'formticket' => $formticket->createView()

        ));
    }

    public function resolveAction(Request $request, $page)

    {

        // Ici, on récupérera l'annonce correspondante à l'id $id

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Tickets');

        $listTickets = $repository->myFindOnetick($page);


        // On crée le FormBuilder grâce au service form factory
        foreach ($listTickets as $ticket) {
            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $ticket);


            // On ajoute les champs de l'entité que l'on veut à notre formulaire

            $formBuilder
                ->add('compterendu', TextareaType::class, array('label' => 'Entrez un compte rendu :'))
                ->add('Envoyer', SubmitType::class, array('label' => 'Clore le Ticket'));
            $formticketcpt = $formBuilder->getForm();

            // Si la requête est en POST

            if ($request->isMethod('POST')) {

                // On fait le lien Requête <-> Formulaire

                // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur

                $formticketcpt->handleRequest($request);


                // On vérifie que les valeurs entrées sont correctes

                // (Nous verrons la validation des objets en détail dans le prochain chapitre)

                if ($formticketcpt->isValid()) {

                    // On enregistre notre objet $advert dans la base de données, par exemple
                    $numdem = $ticket->getDemande();
                    $numdem->setEtat('resolue');

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($ticket);

                    $em->flush();


                    $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


                    // On redirige vers la page de visualisation de l'annonce nouvellement créée
                    return $this->redirectToRoute('ticketing_tech');
                }

            }

        }

        return $this->render('TicketingBundle:Ticketing:resolve.html.twig', array(

            'listTickets' => $listTickets, 'formticketcpt' => $formticketcpt->createView()

        ));
    }

    public function inscriptionAction(Request $request)
    {
        // On crée un objet Advert

        $utilisateur = new Utilisateurs();


        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $utilisateur);


        // On ajoute les champs de l'entité que l'on veut à notre formulaire

        $formBuilder
            ->add('nom', TextType::class, array('label' => 'Nom :'))
            ->add('prenom', TextType::class, array('label' => 'Prénom :'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Répéter Password'),
            ))
            ->add('email', TextType::class, array('label' => 'Email :'))
            ->add('envoyer', SubmitType::class, array('label' => 'Créer mon compte'));

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
        // On récupère le service


// Si l'utilisateur courant est anonyme, $user vaut « anon. »


// Sinon, c'est une instance de notre entité User, on peut l'utiliser normalement

        $utilisateur->setRoles(array('ROLE_USER'));



        $utilisateur->setSalt('');

        // À partir du formBuilder, on génère le formulaire

        $form = $formBuilder->getForm();



        // Si la requête est en POST

        if ($request->isMethod('POST')) {

            // On fait le lien Requête <-> Formulaire

            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur

            $form->handleRequest($request);


            // On vérifie que les valeurs entrées sont correctes


            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            if ($form->isValid()) {


                // On enregistre notre objet $advert dans la base de données, par exemple

                $em = $this->getDoctrine()->getManager();

                $em->persist($utilisateur);

                ;
                $utilisateur->setUsername( $form['nom']->getData() . $form['prenom']->getData());

                $em->flush();


                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ticketing_homepage');
            }



        }
        return $this->render('TicketingBundle:Ticketing:inscription.html.twig', array(

            'form' => $form->createView()

        ));
    }
    public function inscriptiontechAction(Request $request)
    {
        // On crée un objet Advert

        $utilisateur = new Utilisateurs();


        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $utilisateur);


        // On ajoute les champs de l'entité que l'on veut à notre formulaire

        $formBuilder
            ->add('nom', TextType::class, array('label' => 'Nom :'))
            ->add('prenom', TextType::class, array('label' => 'Prénom :'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Répéter Password'),
            ))
            ->add('email', TextType::class, array('label' => 'Email :'))
            ->add('envoyer', SubmitType::class, array('label' => 'Créer mon compte'));

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
        // On récupère le service


// Si l'utilisateur courant est anonyme, $user vaut « anon. »


// Sinon, c'est une instance de notre entité User, on peut l'utiliser normalement

        $utilisateur->setRoles(array('ROLE_TECH'));



        $utilisateur->setSalt('');

        // À partir du formBuilder, on génère le formulaire

        $form = $formBuilder->getForm();



        // Si la requête est en POST

        if ($request->isMethod('POST')) {

            // On fait le lien Requête <-> Formulaire

            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur

            $form->handleRequest($request);


            // On vérifie que les valeurs entrées sont correctes


            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            if ($form->isValid()) {


                // On enregistre notre objet $advert dans la base de données, par exemple

                $em = $this->getDoctrine()->getManager();

                $em->persist($utilisateur);

                ;
                $utilisateur->setUsername( $form['nom']->getData() . $form['prenom']->getData());

                $em->flush();


                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ticketing_homepage');
            }



        }
        return $this->render('TicketingBundle:Ticketing:inscriptiontech.html.twig', array(

            'form' => $form->createView()

        ));


    }
    public function profilesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('ticketing_homepage');

        }

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingUserBundle:Utilisateurs');


        $users = $repository->myFindAll();
        return $this->render('TicketingBundle:Ticketing:profiles.html.twig', array('users' => $users));
    }
}
