<?php
namespace APP\Controller;

use Slim\Views\Twig as TwigViews;

/**
 * Class AbstractController
 *
*/

class AbstractController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}
