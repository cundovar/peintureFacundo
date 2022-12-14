<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireCreateController 
{

    public function __invoke(Commentaire $data)
    {
        dd($data);
    }
   
    


}
