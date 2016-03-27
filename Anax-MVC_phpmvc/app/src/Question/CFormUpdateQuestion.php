<?php

namespace Anax\Question;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $question;
	public $tags;

    /**
     * Constructor
     *
     */
    public function __construct($question, $tags)
    {
		if(!$question) {
			die('Not a valid question!');
		}
		
		$this->question = $question;
		
        parent::__construct([], [
			'id' => [
				'type'  	  => 'hidden',
				'value'       => $question->id,
				'required'    => true,
				'validation'  => ['not_empty'],
			],
            'content' => [
                'type'        => 'textarea',
				'id'		  => 'textareaEditor',
				'value'       => $question->content,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			'title' => [
                'type'        => 'text',
                'label'       => 'Title:',
				'value'       => $question->title,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			"tags" => [
                    "type"        => "select-multiple",
                    "label"       => "Select one or more tags:",
                    "description" => "Select multiple tags by holding down ctrl/command. If you dont wish to edit the tags, leave untouched.",
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
		$this->tags = new \Anax\Tags\Tags();
        $this->tags->setDI($this->di);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$this->question->save([
			'id' 		=> $this->Value('id'),
			'title' 	=> htmlentities($this->Value('title'), null, 'UTF-8'),
            'content'	=> htmlentities($this->Value('content'), null, 'UTF-8'),
			'updated'	=> $now,
		]);
		
		$questionID = $this->Value('id');
		
		
		if($this->Value('tags')[0] != null) { 
			$this->di->db->delete(
				'tag2question',
				'questionID = ?'
			);
			$this->di->db->execute([$questionID]);
			
			foreach ($this->Value('tags') as $tag => $id) {
				 $this->di->db->insert(
					'tag2question',
					['tagID', 'questionID']
				);
				$this->di->db->execute([$id, $questionID]);
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
    }
}
