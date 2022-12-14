<?php

namespace App\Controller;

use App\Entity\Oeuvres;
use App\Form\Oeuvres2Type;
// use App\Form\OeuvresType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\OeuvresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OeuvresController extends AbstractController
{
    /**
     * @Route("/oeuvres/", name="app_oeuvres_index", methods={"GET","POST"})
     */
    public function index(OeuvresRepository $oeuvresRepository): Response
    {
        $oeuvre=$oeuvresRepository->findByCategorie(2);
        
        return $this->render('main/index.html.twig',[
            "oeuvres"=>$oeuvre
        ]);
    }

    /**
     * @Route("/oeuvres/new", name="app_oeuvres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OeuvresRepository $oeuvresRepository): Response
    {
        $oeuvre = new Oeuvres();
        $form = $this->createForm(Oeuvres2Type::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oeuvresRepository->add($oeuvre, true);

            return $this->redirectToRoute('app_oeuvres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvres/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    /**
    * @Route("/oeuvres/art_digital",name="artDigital",methods={"GET"} )
    */
   
       public function artDigital(OeuvresRepository $oeuvresRepository,Request $request,PaginatorInterface $paginator): Response
       {
   
           $donnees=$oeuvresRepository->findByCategorie(1);
           $oeuvre=$paginator->paginate(
                  $donnees,
                  $request->query->getInt('page',1),
                  5
           );
           return $this->render('oeuvres/art_digital.html.twig',[
               "oeuvres"=>$oeuvre
           ]);
   
       }
   /**
    * @Route("/oeuvres/peinture",name="peinture",methods={"GET"} )
    */
   
   public function peinture(OeuvresRepository $oeuvresRepository,Request $request,PaginatorInterface $paginator): Response
   {
   
       $donnees=$oeuvresRepository->findByCategorie(2);
       $oeuvre=$paginator->paginate(
        $donnees,
        $request->query->getInt('page',1),
        5
 );

       return $this->render('oeuvres/peinture.html.twig',[
           "oeuvres"=>$oeuvre
       ]);
   
   }
    /**
     * @Route("/oeuvres/mention_legal", name="mention_legal")
     */
    public function mention_legal()
    {
        return $this->render('oeuvres/mentionLegal.html.twig');
    }


    /**
     * @Route("/oeuvres/{id}", name="app_oeuvre_show")
     */
    public function show(Oeuvres $oeuvre,Request $request,EntityManagerInterface $manager,CommentaireRepository $repoCommentaire): Response
    {
        $commentaires = $repoCommentaire->findBy([
            // key (nom de la propriété dans l'entity) => value
            'oeuvre' => $oeuvre
        ]);
        /*

            findAll() ==> SELECT * FROM commentaire
            find($id) ==> SELECT * FROM commentaire WHERE id = $id
            findBy() ==> l'argument est un tableau

            SELECT * FROM commentaire WHERE produit = ..

        */


       
        // dd($commentaires);
        $commentaire = new Commentaire;
        
        $formComment = $this->createForm(CommentaireType::class, $commentaire);

        // dd($commentaire);

        $formComment->handleRequest($request);

        if($formComment->isSubmitted() AND $formComment->isValid())
        {
            $user = $this->getUser();

            $commentaire->setOeuvre($oeuvre);
            $commentaire->setUser($user);
            $commentaire->setDateAt(new \DateTimeImmutable('now'));

            $manager->persist($commentaire);
            $manager->flush();

            $repoCommentaire->add($commentaire, true);

            $this->addFlash("success", "Merci pour votre avis");
            return $this->redirectToRoute("app_oeuvre_show", ["id" => $oeuvre->getId()]);
        }





        return $this->render('oeuvres/show.html.twig', [
            'oeuvre' => $oeuvre,
            "formComment" => $formComment->createView(),
            "commentaires" => $commentaires
        ]);
    }
   


    

    

    /**
     * @Route("/oeuvres/{id}/edit", name="app_oeuvres_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Oeuvres $oeuvre, OeuvresRepository $oeuvresRepository): Response
    {
        $form = $this->createForm(OeuvresType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oeuvresRepository->add($oeuvre, true);

            return $this->redirectToRoute('app_oeuvres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvres/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/oeuvres/{id}", name="app_oeuvres_delete", methods={"POST"})
     */
    public function delete(Request $request, Oeuvres $oeuvre, OeuvresRepository $oeuvresRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $oeuvresRepository->remove($oeuvre, true);
        }

        return $this->redirectToRoute('app_oeuvres_index', [], Response::HTTP_SEE_OTHER);
    }

     
    
 




}