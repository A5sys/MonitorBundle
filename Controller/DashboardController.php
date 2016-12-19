<?php

namespace A5sys\MonitorBundle\Controller;

use A5sys\MonitorBundle\Services\MonitorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class DashboardController extends Controller
{
    /**
     * @Template()
     * @Route("/", name="monitor_dashboard")
     *
     * @return Response
     */
    public function indexAction()
    {
        $finder = new Finder();
        $logPath = $this->container->getParameter('kernel.logs_dir');
        $finder->files()->name('monitor-*-*-*.log');

        $sort = function (\SplFileInfo $a, \SplFileInfo $b) {
            return strcmp($b->getRealPath(), $a->getRealPath());
        };

        $files = $finder->in($logPath)->sort($sort);

        return ['files' => $files];
    }

    /**
     * @Template()
     * @Route("/day/{filename}", name="monitor_day")
     *
     * @param string $filename
     * @return Response
     */
    public function dayAction($filename)
    {
        $logPath = $this->container->getParameter('kernel.logs_dir');
        $file = new File($logPath.'/'.$filename);

        $configuration = $this->container->getParameter('monitor.configuration.types');

        /* @var $monitorService MonitorService */
        $monitorService = $this->get('monitor.services.monitor_service');

        //1500 ms is the limit
        $data = $monitorService->compute($file);

        return ['filename' => $filename, 'data' => $data, 'configuration' => $configuration];
    }
}
