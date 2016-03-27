<?php

namespace Anax\Tags;
 
/**
 * Model for forum questions.
 *
 */
class Tags extends \Anax\Database\CDatabaseModel
{
	public function prepareForForm() {
		$tags = $this->findAll();
		
		$prepared = array();
		foreach ($tags as $tag) {
			$prepared[$tag->id] = $tag->title;
		}
		
		return $prepared;
	}
	
	public function getMostUsed($count = 10)
    {
        $this->db->select('t.id, t.title, count(*) as count')
            ->from('tag2question AS t2q')
            ->join('tags AS t', 't2q.tagID = t.id')
            ->groupBy('tagID')
            ->orderBy('count(*) DESC')
            ->limit($count);
        $this->db->execute();
        return $this->db->fetchAll();
    }
}