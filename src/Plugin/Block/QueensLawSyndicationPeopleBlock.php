<?php

namespace Drupal\queenslaw_syndication\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides blocks syndicated from the main Queen's University Faculty of Law website.
 *
 * @Block(
 *   id = "queenslaw_syndication_people",
 *   admin_label = @Translation("Syndicated people block"),
 * )
 */
class QueensLawSyndicationPeopleBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['display'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Display'),
      '#description' => $this->t('Provide the Views display machine name of the source view to be syndicated.'),
      '#required' => TRUE,
      '#default_value' => isset($config['display']) ? $config['display'] : '',
    ];
    $form['tids'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term IDs'),
      '#description' => $this->t('Provide the term IDs to be passed to the view as arguments. If multiple, separate with “+” (e.g. “1+2+3”).'),
      '#required' => TRUE,
      '#default_value' => isset($config['tids']) ? $config['tids'] : '',
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
    if (isset($config['display']) && (isset($config['tids'])) && ($content = _queenslaw_syndication_people_content($config['display'], $config['tids']))) {
      if (isset($content['markup'])) {
        // using "children" here instead of "markup" preserves the inline styles used by
        // the Color Field module; ref. https://drupal.stackexchange.com/questions/184963/pass-raw-html-to-markup
        $return['#children'] = $content['markup'];
        $return['#attached'] = [
          'library' => ['queenslaw_syndication/queenslaw_syndication'],
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
