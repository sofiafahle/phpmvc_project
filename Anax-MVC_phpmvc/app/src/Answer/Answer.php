<?php

namespace Anax\Answer;
 
/**
 * Model for forum questions.
 *
 */
class Answer extends \Anax\Database\CDatabaseModel
{
	public function findRelated($answers) {
		$answers = $this->findUser($answers);
		foreach ($answers as $answer) {
			$answer->setDI($this->di);
			$answer = $this->findParent($answer, $answer->questionID);
		}
		
		return $answers;
	}
}