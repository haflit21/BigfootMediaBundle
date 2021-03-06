<?php

namespace Bigfoot\Bundle\MediaBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\Container;

/**
 * Helper filter facilitating the display of an image from the portfolio.
 * Converts an id or list of id separated with commas by the relative path to the first media found.
 *
 * Class MediaExtension
 * @package Bigfoot\Bundle\MediaBundle\Twig
 */
class MediaExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('media', array($this, 'mediaFilter'))
        );
    }

    /**
     * @param $value
     * @return string
     */
    public function mediaFilter($value)
    {
        $ids = explode(';',$value);
        $em = $this->container->get('doctrine')->getManager();
        $request = $this->container->get('request');
        $result = $em->getRepository('Bigfoot\Bundle\MediaBundle\Entity\Media')->findOneBy(array('id' => $ids));

        if (!$result) {
            return '';
        }

        return sprintf('%s/%s', $request->getBasePath(), $result->getFile());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'media';
    }
}