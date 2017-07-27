<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-16
 * Time: 17:04
 */

namespace AppBundle\Controller\User;

//use AppBundle\Entity\User;
use AppBundle\Table\FriendsSearchTableType;
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
        return $this->render('user/friends/index.html.twig',
            [
            ]
        );
    }

    /**
     * @Route("/user/friends/search", name="user_friends_search")
     */
    public function searchAction(Request $request)
    {
        $table = $this->get('jgm.table')->createTable(new FriendsSearchTableType());

        return $this->render('user/friends/search.html.twig',
            [
                'userTable' => $table->createView()
            ]
        );
    }

    /**
     * @Route("/user/friends/invitations", name="user_friends_invitations")
     */
    public function invitationsAction(Request $request)
    {

        return $this->render('user/friends/invitations.html.twig',
            [
            ]
        );
    }
}