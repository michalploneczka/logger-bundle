<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\Controller;

use Abc\Bundle\LoggerBundle\Logger\Registry;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class LogController extends FOSRestController
{

    /**
     * Creates a log entry.
     *
     * @ApiDoc(
     *   section="AbcLoggerBundle",
     *   statusCodes = {
     *     204 = "Returned on success",
     *     400 = "Returned in case of a validation error"
     *   }
     * )
     *
     * @Post("/log/{application}")
     *
     * @RequestParam(name="level", requirements="(emergency|alert|critical|error|warning|notice|info|debug)", description="The log level", strict=true, nullable=false)
     * @RequestParam(name="message", description="The log message", strict=true, nullable=false)
     * @RequestParam(name="context", description="The context map", nullable=true)
     *
     * @param string $application The name of the client application
     * @param ParamFetcherInterface $paramFetcher
     * @return void
     */
    public function logAction($application, ParamFetcherInterface $paramFetcher)
    {
        $level   = $paramFetcher->get('level');
        $message = $paramFetcher->get('message');
        $context = $paramFetcher->get('context');

        $this->getRegistry()->get($application)->log($level, $message, $context == null ? [] : $context);

        return null;
    }

    /**
     * @return Registry
     */
    protected function getRegistry()
    {
        return $this->get('abc.logger.registry');
    }
}