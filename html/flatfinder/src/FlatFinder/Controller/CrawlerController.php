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

class CrawlerController extends Controller
{

    /**
     * Crawl for new entries
     * @Route("/crawler/crawl", name="crawler-crawl")
     */
    public function crawlAction(Request $request)
    {
        $crawler = $this->get('crawler');
        $crawler->getData();

        exit('OK');
    }
}