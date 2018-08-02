<?php
	namespace App\Controller;

	use App\Entity\Film;

	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\IntegerType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\HttpFoundation\Request;

	class FilmController extends Controller{
		/**
		* @Route("/", name="film_list")
		* @Method({"GET"})
		*/
		public function index(Request $request){

			$em    = $this->get('doctrine.orm.entity_manager');
		    $dql   = "SELECT f FROM App\Entity\Film f";
		    $query = $em->createQuery($dql);

		    $paginator  = $this->get('knp_paginator');
		    $pagination = $paginator->paginate(
		        $query, /* query NOT result */
		        $request->query->getInt('page', 1)/*page number*/,
		        5/*limit per page*/
		    );

		    return $this->render('films/index.html.twig', array('pagination' => $pagination));
		}

		

		/**
		* @Route("/film/new", name="new_film")
		* Method({"GET", "POST"})
		*/
		public function new(Request $request){
			$film = new Film();
			$form = $this->CreateFormBuilder($film)
				->add('title', TextType::class, array('attr'=>array('class'=>'form-control')))
				->add('image_url', TextType::class, array(
					'required'=> false,
					'attr'=>array('class'=>'form-control')
				))
				->add('description', TextareaType::class, array(
					'required'=> false,
					'attr'=>array('class'=>'form-control')
				))
				->add('score', IntegerType::class, array(
					'required'=> false,
					'attr'=>array('class'=>'form-control mt-3')
				))
				->add('save', SubmitType::class, array(
					'label'=>'create',
					'attr'=>array('class'=>'btn btn-light mt-3')
				))
				->getForm();

			$form->handleRequest($request);
			if($form->isSubmitted() && $form->isValid()){
				$film = $form->getData();
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($film);

				

				$entityManager->flush();

				return $this->redirectToRoute('film_list');
			}

			return $this->render('films/new.html.twig', array(
				'form'=>$form->createView()
			));
		}

		

		/**
		* @Route("/film/{id}", name="film_show")
		*/
		public function show($id){
			$film = $this->getDoctrine()->getRepository(Film::class)->find($id);

			return $this->render('films/show.html.twig', array('film' => $film));
		}

	}