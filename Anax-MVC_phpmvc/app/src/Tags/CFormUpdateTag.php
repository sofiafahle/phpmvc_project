<?php

namespace Anax\Tags;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateTag extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $user;

    /**
     * Constructor
     *
     */
    public function __construct($user)
    {
		if(!$user) {
			die('Not a valid user!');
		}
		
		$this->user = $user;
		
        parent::__construct([], [
			'title' => [
                'type'        => 'text',
                'label'       => 'Title:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
		$now = gmdate('Y-m-d H:i:s');
		
		$inactive = $this->Value('inactivate') ? $now : null;
		$deleted = $this->Value('delete') ? $now : null;
		
		$this->user->save([
			'title' => htmlentities(strip_tags($this->Value('title'))),
			'updated' => $now,
		]);

        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->redirectTo('tags');
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
		$this->redirectTo();
    }
}
