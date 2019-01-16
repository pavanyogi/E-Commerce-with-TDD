<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CalculatorController extends Controller
{
    public function add($a, $b)
    {
        return $a * $b;
    }

    /**
     * @Route("/calculator", name="calculator", methods={"GET", "POST"})
     * @Template()
     */
    public function calculatorAction(Request $request)
    {
        $name = 'Prafulla Meher';
        $form = $this->createFormBuilder()
            ->add('task', TextType::class, array('label' => 'Task'))
            ->add('dueDate', DateType::class)
            ->add('submit', SubmitType::class, array('label' => 'submit'))
            ->getForm();
        return $this->render('calculator/calculator.html.twig', [
            'name' => $name,
            'form' => $form->createView()
        ]);
    }
}
