<?php

namespace Drupal\queenslaw_syndication\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides blocks syndicated from the main Queen's University Faculty of Law website.
 *
 * @Block(
 *   id = "queenslaw_syndication_notifications",
 *   admin_label = @Translation("Syndicated notification block"),
 * )
 */
class QueensLawSyndicationNotificationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $return = [
      '#cache' => [
        'max-age' => 0,
        'contexts' => [],
        'tags' => [],
      ],
    ];
    if ($content = _queenslaw_syndication_notifications_content()) {
      if (isset($content['markup'])) {
        $return = [
          '#children' => $content['markup'],
        ];
        if (isset($content['base_class'])) {
          $return['#attributes'] = [
            'class' => [
              'block-' . $content['base_class'],
            ],
          ];
        }
      }
    }
    return $return;
  }
}
