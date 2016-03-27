<?php

namespace Anax\Question;
 
/**
 * Model for forum questions.
 *
 */
class Question extends \Anax\Database\CDatabaseModel
{
	
	/**
	 * Find most recent items. 
	 *
	 * @param int $count as number of items to return.
	 *
	 * @return array $mostRecent as sorted array.
	 */
	public function getMostRecent($count = 10) {
		$this->db->select()->from('question')->where('deleted IS NULL AND inactivated IS NULL')->orderBy('created DESC')->limit(3)->execute();
		$this->db->setFetchModeClass('Anax\Question\Question');
		$mostRecent = $this->db->fetchAll();
		
		$mostRecent = $this->findUser($mostRecent);
		
		return $mostRecent;
	}
}