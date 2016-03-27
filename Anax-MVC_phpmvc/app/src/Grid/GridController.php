<?php

namespace Sofa15\Grid;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class GridController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
	
	
	public function regionsAction() {
		// Add extra assets
		$this->theme->addStylesheet('css/anax-grid/grid_preview.less');
		
		   
		$this->theme->setTitle("Regioner");
		
		$this->views->addString('flash', 'flash')
		   ->addString('feature-1', 'feature-1')
		   ->addString('feature-2', 'feature-2')
		   ->addString('feature-3', 'feature-3')
		   ->addString('main', 'main')
		   ->addString('sidebar', 'sidebar')
		   ->addString('triptych-1', 'triptych-1')
		   ->addString('triptych-2', 'triptych-2')
		   ->addString('triptych-3', 'triptych-3')
		   ->addString('footer-col-1', 'footer-col-1')
		   ->addString('footer-col-2', 'footer-col-2')
		   ->addString('footer-col-3', 'footer-col-3')
		   ->addString('footer-col-4', 'footer-col-4');
	}
	
	public function themeAction() {
		
		$this->theme->setTitle("Tema");
		
		$this->views->add('test-theme/flash', [], 'flash')
		   ->add('test-theme/main', [], 'main')
		   ->add('test-theme/sidebar', [], 'sidebar')
		   ->add('test-theme/triptych-1', [], 'triptych-1')
		   ->add('test-theme/triptych-2', [], 'triptych-2')
		   ->add('test-theme/triptych-3', [], 'triptych-3');
	}
	
	public function typographyAction() {
		// Add extra assets
		$this->theme->addStylesheet('css/anax-grid/grid_preview.less');
		
		$this->theme->setTitle("Typografi");
	
		$this->views->add('typography/typography', [], 'main')
               ->add('typography/typography', [], 'sidebar');
	}
	
	public function fontAction() {
		
		$this->theme->setTitle("Font Awesome");
	
		$this->views->add('font-awesome/main', [], 'main')
               ->add('font-awesome/sidebar', [], 'sidebar');
	}
}
