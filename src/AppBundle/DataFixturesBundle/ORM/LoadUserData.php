<?php
namespace AppBundle\DataFixturesBundle\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Piotr');
        $user->setLastname('Religa');
        $user->setEmail('test@test.pl');
        $user->setLogin('preliga');

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->endocePassword($user, 'test');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}