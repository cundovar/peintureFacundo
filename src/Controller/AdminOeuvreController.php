<?php

namespace App\Controller;

use App\Entity\Oeuvres;
use App\Form\Oeuvres2Type;
use App\Repository\OeuvresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin/oeuvre")
 */
class AdminOeuvreController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_oeuvre_index", methods={"GET"})
     */
    public function index(OeuvresRepository $oeuvresRepository): Response
    {
        return $this->render('admin_oeuvre/index.html.twig', [
            'oeuvres' => $oeuvresRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_oeuvre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OeuvresRepository $oeuvresRepository,EntityManagerInterface $manager): Response
    {
        $oeuvre = new Oeuvres();
        $form = $this->createForm(Oeuvres2Type::class, $oeuvre,[
            'ajouter'=>true
           
        ] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oeuvre->setDateAt(new \DateTimeImmutable('now'));
            $oeuvresRepository->add($oeuvre, true);

            
            $imageFile = $form->get('image')->getData();

            if($imageFile)
            {
                
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();
             
                $imageFile->move(
                    $this->getParameter("imageOeuvre"),
                    $nomImage
                );
               
                $oeuvre->setImage($nomImage);
            }

            $manager->persist($oeuvre);
            $manager->flush();

         
            $this->addFlash("success", "Le produit N°" .  $oeuvre->getId()  .  " a bien été ajouté");




            return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        

        return $this->renderForm('admin_oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_oeuvre_show", methods={"GET"})
     */
    public function show(Oeuvres $oeuvre,OeuvresRepository $oeuvresRepository): Response
    {
        return $this->render('admin_oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
            // 'oeuvre' => $oeuvresRepository->findOneById(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_oeuvre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Oeuvres $oeuvre, OeuvresRepository $oeuvresRepository,EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(Oeuvres2Type::class, $oeuvre,[
            'modifier'=>true
        ] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oeuvresRepository->add($oeuvre, true);
            
            $imageFile = $form->get('imageUpdate')->getData();
            if($imageFile)
            {
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();

                $imageFile->move(
                    $this->getParameter("imageOeuvre"),
                    $nomImage
                );

              
                if($oeuvre->getImage())
                {
                  
                    unlink($this->getParameter("imageOeuvre") . "/" .$oeuvre->getImage() );
               
                }

                $oeuvre->setImage($nomImage);
            }
            
        $manager->persist($oeuvre);
        $manager->flush();
      

        $this->addFlash("success", "Le produit N°". $oeuvre->getId()." a bien été supprimé");

      
            return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_oeuvre_delete", methods={"POST"})
     */
    public function delete(Request $request, Oeuvres $oeuvre, OeuvresRepository $oeuvresRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $oeuvresRepository->remove($oeuvre, true);
        }
        if($oeuvre->getImage()){
            unlink($this->getParameter('imageOeuvre')."/".$oeuvre->getImage());
            
        }

        return $this->redirectToRoute('app_admin_oeuvre_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/image/supprimer/{id}", name="oeuvre_image_supprimer")
     */
    public function oeuvre_image_supprime(Oeuvres $oeuvre, EntityManagerInterface $manager)
    {
        unlink($this->getParameter('imageOeuvre')."/".$oeuvre->getImage());
        $oeuvre->setImage(NULL);
        $manager->persist($oeuvre);
        $manager->flush();
        // $this->addFlash("success", "L'image du produit N°" . $oeuvre->getId() . " a bien été supprimée");
        return $this->redirectToRoute("app_admin_oeuvre_edit", ["id" => $oeuvre->getId()]);
    }
}
