<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz
 * Date: 2015-11-13
 * Time: 12:31
 */
namespace FlatFinder\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('FlatFinder:home:index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'googleMapsApiKey' => 'AIzaSyBR1SH3srBLIRnTntbm6L4b_i-D64xdOxo',
        ));
    }
}