<?php

namespace Drupal\queenslaw_syndication\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides blocks syndicated from the main Queen's University Faculty of Law website.
 *
 * @Block(
 *   id = "queenslaw_syndication_events",
 *   admin_label = @Translation("Syndicated event block"),
 * )
 */
class QueensLawSyndicationEventBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $event_types = _queenslaw_syndication_event_types();
    $config = $this->getConfiguration();
    $form['event_type'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Type(s)'),
      '#options' => $event_types,
      '#description' => $this->t('Select the type(s) of events displayed.'),
      '#required' => TRUE,
      '#default_value' => isset($config['event_type']) ? $config['event_type'] : '',
    ];
    $form['event_count'] = [
      '#type' => 'select',
      '#title' => $this->t('Number'),
      '#options' => range(1, 20),
      '#description' => $this->t('Select the number of events displayed.'),
      '#required' => TRUE,
      '#default_value' => isset($config['event_count']) ? $config['event_count'] : '',
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
    if (isset($config['event_type']) && (isset($config['event_count'])) && ($content = _queenslaw_syndication_event_block_content($config['event_type'], $config['event_count']))) {
      if (isset($content['markup'])) {
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
