<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends Controller
{
    /**
     * @Route("/agent/list", name="agent_list", methods={"GET", "OPTIONS"})
     */
    public function getAgentListAction(Request $request)
    {

    }

    /**
     * @Route("/agent/detail", name="agent_detail", methods={"GET", "OPTIONS"})
     */
    public function getAgentDetailAction(Request $request)
    {

    }

    /**
     * @Route("/agent/create", name="agent_create", methods={"POST", "OPTIONS"})
     */
    public function createAgentAction(Request $request)
    {

    }

    /**
     * @Route("/product/update", name="agent_update", methods={"PUT", "OPTIONS"})
     */
    public function updateAgentAction(Request $request)
    {

    }
}
