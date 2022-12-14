<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil(): Response
    {

        dump($this->getUser());
        $user = $this->getUser();
        return $this->render('profil/profil.html.twig', [
            "user" => $user
         ]);

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("/commandes",name="commande")
     */
    public function commandes(CommandeRepository $repoCommande)

    {
        $user=$this->getUser();
        $commandes=$repoCommande->findBy(["User"=>$user] );
return $this->render('profil/commandes.html.twig',["commandes"=>$commandes] );

    }


}
