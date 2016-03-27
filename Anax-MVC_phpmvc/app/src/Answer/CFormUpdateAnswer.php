<?php

namespace Anax\Answer;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $answer;

    /**
     * Constructor
     *
     */
    public function __construct($answer)
    {
		if(!$answer) {
			die('Not a valid answer!');
		}
		
		$this->answer = $answer;
		
        parent::__construct([], [
			'id' => [
				'type'  	  => 'hidden',
				'value'       => $answer->id,
				'required'    => true,
				'validation'  => ['not_empty'],
			],
            'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
				'value'       => $answer->content,
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
		
		$this->answer->save([
			'id' 		=> $this->Value('id'),
            'content'	=> htmlentities($this->Value('content'), null, 'UTF-8'),
			'updated'	=> $now,
		]);
		
        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOUtput("<p><i>Form was submitted and the answer was updated successfully.</i></p>");
        $this->redirectTo('question/view/' . $this->answer->questionID);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
    }
}
