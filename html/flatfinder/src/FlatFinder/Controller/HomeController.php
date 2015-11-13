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
        // FIXME
        $dataUrl = 'http://www.immobilienscout24.de/Suche/S-T/Wohnung-Miete/Berlin/Berlin/-/-/-/EURO--400,00?enteredFrom=one_step_search';
        $data = file_get_contents($dataUrl);
        preg_match('/model: {"results":(.+)travelTimeModel/msU', $data, $matches);

        $match = substr($matches[1], 0, -10);
        unset($data, $matches);

//        exit('<pre>' . print_r($match,1));


        return $this->render('FlatFinder:home:index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'googleMapsApiKey' => 'AIzaSyBR1SH3srBLIRnTntbm6L4b_i-D64xdOxo',
            'markerData'       => $match,
        ));
    }
}