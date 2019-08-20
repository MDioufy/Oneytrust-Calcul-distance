<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
class DefaultController extends Controller
{
    /**
     * @Route("/calculate", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createAddressForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $depart = $data['RueDepart'].', '.$data['CpDepart'].' '.$data['VilleDepart'].' '.$data['PaysDepart'];
            $destination = $data['RueArrivee'].', '.$data['CpArrivee'].' '.$data['VilleArrivee'].' '.$data['PaysArrivee'];

            $distance = $this->calculateDistance($depart, $destination);
             if($distance !== null){
                    $this->addFlash('success', 'La distance entre '.$depart.' et ' .$destination. ' est de : '.$distance);
                }
        }
        
        return $this->render('distance/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    
    /**
     * Calcul de la distance entre les deux adresses
     * @param string $from
     * @param string $to
     * @return type
     */
    protected function calculateDistance(string $from, string $to)
    {
        $from1 = urlencode($from);
        $to1 = urlencode($to);
        $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$from1."&destinations=".$to1."&key=MY-API-KEY");
        
        $data = json_decode($api);
        $distance  = (int)$data->rows[0]->elements[0]->distance->value/1000;
        
        return $distance.' Km';
    }
    
    /**
     * Création du formulaire des adresses
     * @return type
     */
    protected function createAddressForm()
    {   
        return $this->createFormBuilder()
                //coordonnées de départ
               ->add('RueDepart', Type\TextareaType::class, [
                   'label' => 'Rue',
                    'attr' =>['placeholder' => 'N° et libellé de voie',],
      
                ])
                ->add('CpDepart', Type\TextType::class, [
                   'label' => 'Code postal',
                    'attr' =>['placeholder' => 'Ex: 75000',],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir le code postal',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Le code poste doit au mois {{ limit }} chiffres',
                            'max' => 5,
                        ]),
                    ],
                ])
                ->add('VilleDepart', Type\TextType::class, [
                   'label' => 'Ville',
                    'attr' =>['placeholder' => 'Ex: Paris',],
                ])
                ->add('PaysDepart', Type\TextType::class, [
                   'label' => 'Pays',
                    'attr' =>['placeholder' => 'Ex: France',],
                ])
                //coordonnées d'arrivéée
                ->add('RueArrivee', Type\TextareaType::class, [
                   'label' => 'Rue',
                    'attr' =>['placeholder' => 'N° et libellé de voie',],
                ])
                ->add('CpArrivee', Type\TextType::class, [
                   'label' => 'Code postal',
                     'attr' =>['placeholder' => 'Ex: 78000',],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir le code postale',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Le code poste doit au mois {{ limit }} chiffres',
                            'max' => 5,
                        ]),
                    ],
                ])
                ->add('VilleArrivee', Type\TextType::class, [
                   'label' => 'Ville',
                    'attr' =>['placeholder' => 'Ex: Versailles',],
                ])
                ->add('PaysArrivee', Type\TextType::class, [
                   'label' => 'Pays',
                    'attr' =>['placeholder' => 'Ex: France',],
                ])
                ->getForm();
        
    }
  
}
