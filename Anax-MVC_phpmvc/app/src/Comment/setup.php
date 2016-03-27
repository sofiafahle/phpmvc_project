<?php
$this->db->dropTableIfExists('comment')->execute();
		
$this->db->createTable(
	'comment',
	[
		'id'			=> ['integer', 'primary key', 'not null', 'auto_increment'],
		'parentID'		=> ['integer', 'not null'],
		'parentType'	=> ['varchar(20)', 'not null'],
		'userID'		=> ['integer', 'not null'],
		'content'		=> ['text'],
		'created'		=> ['datetime'],
		'updated'		=> ['datetime'],
		'deleted'		=> ['datetime'],
	]
)->execute();

 $this->db->insert(
	'comment',
	['parentID', 'parentType', 'userID', 'content', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
	'1',
	'Question',
	'4',
	'He probably had it coming!',
	$now,
]);

$this->db->execute([
	'1',
	'Answer',
	'2',
	'Could you elaborate?',
	$now,
]);