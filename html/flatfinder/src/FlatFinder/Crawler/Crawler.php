<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 14.11.15
 * Time: 00:14
 */

namespace FlatFinder\Crawler;


use FlatFinder\Crawler\Source\Immobilienscout;

/**
 * Class Crawler
 * @package FlatFinder\Crawler
 */
class Crawler {

    private $entityManager;

    /**
     * Constructor class
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    public function getData()
    {
        $source = new Immobilienscout();

        $source->getData();
    }
} 