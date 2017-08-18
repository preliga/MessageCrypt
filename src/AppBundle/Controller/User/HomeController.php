<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 15:18
 */

class HomeController extends Controller
{
    /**
     * @Route("/user/home/index", name="user_home_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('user/home/index.html.twig',
            [
            ]
        );
    }



//    /**
//     * @Route("/{action}/{params}", name="default_dynamic")
//     */
//    public function dynamicAction($action, $params = null)
//    {
//        $action = $action . 'Action';
//        if (method_exists($this, $action)) {
//            return $this->{$action}($params);
//        }
//
//        return $this->indexAction();
//    }

}