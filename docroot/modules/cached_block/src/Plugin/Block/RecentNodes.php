<?php

namespace Drupal\cached_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a block with latest 5 published nodes of any type
 *
 * @Block(
 *   id = "recent_nodes",
 *   admin_label = @Translation("Recent 5 nodes")
 * )
 */
class RecentNodes extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity query factory.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * Entity type manager service
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Current user
   *
   * @var AccountProxy
   */
  protected $currentUser;

  /**
   * Constructs a new BookNavigationBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param QueryFactory $entityQuery
   *   Entity query factory.
   * @param EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, QueryFactory $entityQuery, EntityTypeManagerInterface $entityTypeManager, AccountProxy $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // node_get_recent() could be used too.
    // but I find it cleaner to provide dependencies through DI

    $nids = $this->entityQuery->get('node')
      ->condition('status', 1)
      ->condition('uid', $this->currentUser->id())
      ->sort('changed', 'DESC')
      ->range(0, 5)
      ->execute();

    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);

    $titles = '';
    $cacheTags = [];

    /**
     * @var int $nid
     * @var Node $node
     */
    foreach ($nodes as $nid => $node) {
      $titles .= '<p>'.$node->getTitle().'</p>';
      $cacheTagsArray = $node->getCacheTags();
      $cacheTags[] = reset($cacheTagsArray);
    }

    $cacheTags[] = 'node:add';

    return [
      '#markup' => $titles,
      '#cache' => [
        'keys' => array('my-key-1', 'my-key-2'),
        'tags' => $cacheTags,
        'contexts' => array('user')
      ]
    ];
  }
}
