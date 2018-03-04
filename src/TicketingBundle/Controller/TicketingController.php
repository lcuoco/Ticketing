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
use Symfony\Component\HttpFoundation\Response;
use TicketingUserBundle\TicketingUserBundle;

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
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_DEM')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException('Accès limité aux Utilisateurs.');
        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Demandes');


        $listDemandes = $repository->myFindOne($this->getUser());
        return $this->render('TicketingBundle:Ticketing:user.html.twig', array('listDemandes' => $listDemandes));
    }

    public function techAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_RAPP')) {

            // Sinon on déclenche une exception « Accès interdit »

            throw new AccessDeniedException('Accès limité aux Techniciens.');

        }
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('TicketingBundle:Tickets');


        $listTickets = $repository->myFindOnea($this->getUser());
        return $this->render('TicketingBundle:Ticketing:tech.html.twig', array('listTickets' => $listTickets));
    }

    public function adminAction()
    {
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

        $demande = new Demandes();


        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $demande);


        // On ajoute les champs de l'entité que l'on veut à notre formulaire

        $formBuilder
            ->add('objet', TextType::class)
            ->add('description', TextareaType::class)
            ->add('piecejointe', FileType::class)
            ->add('Envoyer', SubmitType::class);

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
        return $this->render('TicketingBundle:Ticketing:view.html.twig', array(

            'page' => $page, 'ticket' => $ticket

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
            ->add('delai', integerType::class)
            ->add('urgence', CheckboxType::class)
            ->add('utilisateur', EntityType::class, array(
                'class' => Utilisateurs::class, 'choice_label' => 'username',))
            ->add('Envoyer', SubmitType::class);

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
        foreach($demandes as $demande) {
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
                ->add('compterendu', TextareaType::class)
                ->add('Envoyer', SubmitType::class);
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
}
