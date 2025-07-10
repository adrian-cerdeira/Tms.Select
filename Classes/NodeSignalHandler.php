<?php
namespace Tms\Select;

use Neos\Cache\Frontend\VariableFrontend;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Log\Utility\LogEnvironment;
use Psr\Log\LoggerInterface;
use Tms\Select\Service\CachingService;

class NodeSignalHandler
{
    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Flow\Inject
     * @var CachingService
     */
    protected $cachingService;

    /**
     * @var VariableFrontend
     */
    protected $cache;
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     */
    protected function flushDataSourceCaches(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);
        $tag = $this->cachingService->nodeTypeTag($contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName), $node);
        $flushedCacheEntries = $this->cache->flushByTag($tag);
        if ($flushedCacheEntries) {
            $this->logger->debug(
                sprintf('Flushed %s data source cache(s) tagged with: "%s"', $flushedCacheEntries, $tag),
                LogEnvironment::fromMethodName(__METHOD__)
            );
        }
    }

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     */
    public function nodeAdded(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $this->flushDataSourceCaches($node);
    }

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     */
    public function nodeUpdated(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $this->flushDataSourceCaches($node);
    }

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     */
    public function nodeRemoved(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $this->flushDataSourceCaches($node);
    }

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     * @param \Neos\ContentRepository\Core\SharedModel\Workspace\Workspace|null $node
     */
    public function nodePublished(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node, $targetWorkspace = null)
    {
        $this->flushDataSourceCaches($node);
    }

    /**
     * @param \Neos\ContentRepository\Core\Projection\ContentGraph\Node $node
     */
    public function nodeDiscarded(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $this->flushDataSourceCaches($node);
    }
}
