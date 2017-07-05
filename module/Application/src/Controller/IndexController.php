<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Log;
use Zend\Code\Scanner\ClassScanner;
use Zend\Code\Scanner\FileScanner;
use Zend\View\View;
use RestApi\Controller\ApiController;

class IndexController extends AbstractActionController
{

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Post manager.
     * @var Application\Service\LogManager
     */
    private $logManager;


    public $page = 0;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($em, $logManager)
    {
        $this->em = $em;
        $this->logManager = $logManager;
    }

    public function parseLogAction()
    {

        $publicPath = $_SERVER['DOCUMENT_ROOT'];
        $logPathFirst = $publicPath . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'log1.txt';
        $logPathSecond = $publicPath . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'log2.txt';

        $logContentFirst = file($logPathFirst, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logContentSecond = file($logPathSecond, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($logContentFirst as $line => $currentLine) {
            $logContentFirst[$line] .= '|' . $logContentSecond[$line];
        }

        $data = [];

        foreach ($logContentFirst as $line => $currentFile) {
            list($data[$line]['date'], $data[$line]['time'], $data[$line]['ip'],
                $data[$line]['urlFrom'], $data[$line]['urlTo'], $data[$line]['ip'],
                $data[$line]['browser'], $data[$line]['os'])
                = explode('|', $currentFile);
        }

        foreach ($data as $currentLine) {
           // $this->logManager->addNewLog($currentLine);
        }

        $logsRepository = $this->em->getRepository(Log::class);
        $countLog = $logsRepository->countLogs();

        $this->page = $countLog / 4;

        $templateVars = [
            'data' => $data,

        ];
        return new ViewModel($templateVars);
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function logsAction()
    {

        $request = $this->getRequest();

        var_dump($request);

die();
        $this->httpStatusCode = 200;

        // Set the response
        $this->apiResponse['you_response'] = 'your response data';

        return $this->createResponse();
    }

}
