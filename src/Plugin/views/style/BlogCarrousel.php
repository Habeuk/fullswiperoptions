<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 *
 * @ViewsStyle(
 *  id = "blogcarrousel",
 *  title = @Translation("Swiper api : Blog Carrousel "),
 *  help = @Translation(" Add some Slider type for displaying slides "),
 *  theme = "fullswiperoptions_blogcarrousel",
 *  display_types = { "normal" }
 * )
 *
 */
class BlogCarrousel extends Fullswiperoptions {
  
  /**
   * build form options
   *
   * {@inheritdoc}
   *
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['layoutgenentitystyles_view'] = [
      '#type' => 'hidden',
      '#value' => 'fullswiperoptions/blogcarrousel'
    ];
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t(' Container Model '),
      '#options' => [
        'blog-carousel' => 'blog-carousel(no-bullets)',
        'carousel-testy' => 'testy(round-center-black)'
      ],
      '#default_value' => $this->options['theme']
    ];
  }
  
  /**
   * config library and some params
   *
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    // On recupere la valeur de la librairie et on ajoute:
    $library = $this->options['layoutgenentitystyles_view'];
    // dump($library);
    if (empty($library)) {
      $library = 'fullswiperoptions/blogcarrousel';
    }
    
    $this->LayoutgenentitystylesServices->addStyleFromView($library, $this->view->id(), $this->view->current_display);
  }
  
}