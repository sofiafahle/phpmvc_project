<?php

namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;


	public $comment;
	public $questionID;

    /**
     * Constructor
     *
     */
    public function __construct($comment, $questionID)
    {
		if(!$comment) {
			die('Not a valid id!');
		}
		
		$this->comment = $comment;
		$this->questionID = $questionID;
		
		
        parent::__construct([], [
			'id' => [
				'type'  	  => 'hidden',
				'value'       => $comment->id,
				'required'    => true,
				'validation'  => ['not_empty'],
			],
			'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
				'value'		  => $comment->content,
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
		
		$this->comment->save([
			'id' 		=> $this->Value('id'),
			'content' 	=> $this->Value('content'),
			'updated' 	=> $now,
		]);
		
        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
		$this->redirectTo('question/view/' . $this->questionID);
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
