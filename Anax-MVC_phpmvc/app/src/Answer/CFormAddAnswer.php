<?php

namespace Anax\Answer;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $questionID;

    /**
     * Constructor
     *
     */
    public function __construct($questionID)
    {
		$this->questionID = $questionID;
		
        parent::__construct([], [
            'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
				'label'		  => 'Add Answer:',
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
		$this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$this->answer->save([
			'userID'	 => $this->di->session->get('user'),
			'questionID' => $this->questionID,
            'content'	 => htmlentities($this->Value('content'), null, 'UTF-8'),
			'created'	 => $now,
		]);

        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOUtput('<p><i>Form was submitted and the answer was added successfully.</i></p>');
		$this->redirectTo();
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
