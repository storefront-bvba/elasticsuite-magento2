<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Searchandising Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\ElasticsuiteTracker
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticsuiteTracker\Controller\Tracker;

/**
 * Hit event collector.
 *
 * @category Smile
 * @package  Smile\ElasticsuiteTracker
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Hit extends \Magento\Framework\App\Action\Action
{
    /**
     * @var string
     */
    const PIXEL = '6wzwc+flkuJiYGDg9fRwCQLSjCDMwQQkJ5QH3wNSbCVBfsEMYJC3jH0ikOLxdHEMqZiTnJCQAOSxMDB+E7cIBcl7uvq5rHNKaAIA';

    /**
     * @var \Smile\ElasticsuiteTracker\Api\EventQueueInterface
     */
    private $logEventQueue;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\App\Action\Context              $context       Context.
     * @param \Smile\ElasticsuiteTracker\Api\EventQueueInterface $logEventQueue Event queue.
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Smile\ElasticsuiteTracker\Api\EventQueueInterface $logEventQueue
    ) {
        parent::__construct($context);
        $this->logEventQueue = $logEventQueue;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $this->getResponse()->setBody(base64_decode(self::PIXEL));
        $this->getResponse()->setHeader('Content-Type', 'image/png');
        $this->getResponse()->sendResponse();

        $this->logEventQueue->addEvent($this->decodeParams($this->getRequest()->getParams()));
    }

    /**
     * Decode URI params.
     *
     * @param mixed $params Params.
     *
     * @return mixed
     */
    private function decodeParams($params)
    {
        if (is_string($params)) {
            $params = urldecode($params);
        } elseif (is_array($params)) {
            foreach ($params as &$currentParam) {
                $currentParam = $this->decodeParams($currentParam);
            }
        }

        return $params;
    }
}
