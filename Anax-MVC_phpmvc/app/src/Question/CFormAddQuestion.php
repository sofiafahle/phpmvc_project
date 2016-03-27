<?php

namespace Anax\Question;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
		
	public $question;
	public $questionID;
	public $tags;

    /**
     * Constructor
     *
     */
    public function __construct($tags)
    {
        parent::__construct([], [
			'title' => [
                'type'        => 'text',
                'label'       => 'Title:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			"tags" => [
                    "type"        => "select-multiple",
                    "label"       => "Select one or more tags:",
                    "description" => "Select multiple tags by holding down ctrl/command.",
                    "size"        => 5,
                    "options"     => $tags,
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
		$this->question = new \Anax\Question\Question();
        $this->question->setDI($this->di);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$this->question->save([
			'userID'	=> $this->di->session->get('user'),
			'title' 	=> htmlentities($this->Value('title'), null, 'UTF-8'),
            'content'	=> htmlentities($this->Value('content'), null, 'UTF-8'),
			'created'	=> $now,
		]);
		
		$this->questionID = $this->question->db->lastInsertId();
		
		if($this->Value('tags')[0] != null) {
			foreach ($this->Value('tags') as $tag => $id) {
				 $this->di->db->insert(
					'tag2question',
					['tagID', 'questionID']
				);
				$this->di->db->execute([$id, $this->questionID]);
			}
		}
		
        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->redirectTo('question/view/' . $this->question->id);
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
