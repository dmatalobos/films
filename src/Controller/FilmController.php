<?php
	namespace App\Controller;

	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class FilmController extends Controller{
		/**
		* @Route("/")
		* @Method({"GET"})
		*/
		public function index(){
			//return new Response('olá');

			$films = ['Title one','Title two'];

			return $this->render('films/index.html.twig', array('films'=>$films));
		}

	}