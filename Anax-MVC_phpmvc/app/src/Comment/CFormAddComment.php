<?php

namespace Anax\Comment;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $parentType;
	public $parentID;


    /**
     * Constructor
     *
     */
    public function __construct($parentType, $parentID)
    {
		
		$this->parentType = $parentType; 
		$this->parentID = $parentID;
		
        parent::__construct([], [
			'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
				'value'		  => null,
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
		$this->comment = new \Sofa15\Comment\Comment();
        $this->comment->setDI($this->di);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$this->comment->save([
			'parentID'	 => $this->parentID,
			'parentType' => $this->parentType,
			'userID'	 => $this->di->session->get('user'),
			'content'	 => htmlentities($this->Value('content')),
			'created' 	 => $now,
		]);
		
        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOUtput("<p><i>Form was submitted and the comment was added successfully.</i></p>");
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
