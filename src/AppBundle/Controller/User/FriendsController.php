<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FriendsController extends Controller
{
    /**
     * @Route("/user/friends/index", name="user_friends_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('user/home/index.html.twig',
            [
            ]
        );
    }
}