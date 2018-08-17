<?php

namespace Drupal\queenslaw_syndication\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides blocks syndicated from the main Queen's University Faculty of Law website.
 *
 * @Block(
 *   id = "queenslaw_syndication",
 *   admin_label = @Translation("Syndicated block"),
 * )
 */
class QueensLawSyndicationBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['uuid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('UUID'),
      '#description' => $this->t('Provide the UUID of the source block to be syndicated.'),
      '#required' => TRUE,
      '#default_value' => isset($config['uuid']) ? $config['uuid'] : '',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    foreach ($values as $key => $value) $this->configuration[$key] = $value;
  }

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
    $config = $this->getConfiguration();
    if ($content = _queenslaw_syndication_block_content($config['uuid'])) {
      if (isset($content['markup'])) {
        // use #children instead of #markup to avoid having the "style" attribute stripped
//        $return['#markup'] = $content['markup'];
        $return['#children'] = $content['markup'];
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
?>
