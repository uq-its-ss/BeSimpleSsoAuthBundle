<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Tests;

use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * AppKernel
 */
class AppKernel extends Kernel
{
    private $tmpPath;
    private $configFile;

    /**
     * __construct
     *
     * @param string $tmpPath
     * @param string $configFile
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($tmpPath, $configFile, $environment, $debug)
    {
        $this->tmpPath    = $tmpPath;
        $this->configFile = $configFile;

        parent::__construct($environment, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \BeSimple\SsoAuthBundle\BeSimpleSsoAuthBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->tmpPath.'/cache/'.$this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->tmpPath.'/logs';
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->configFile);
    }
}
