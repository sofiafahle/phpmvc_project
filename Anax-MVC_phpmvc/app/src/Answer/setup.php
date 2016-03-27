<?php
$this->db->dropTableIfExists('answer')->execute();
	 
$this->db->createTable(
    'answer',
    [
        'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
		'questionID' => ['integer', 'not null'],
        'userID' => ['varchar(20)', 'not null'],
        'content' => ['text', 'not null'],
		'accepted' => ['datetime'],
        'created' => ['datetime'],
        'updated' => ['datetime'],
        'deleted' => ['datetime'],
        'inactivated' => ['datetime']
    ]
)->execute();

 $this->db->insert(
    'answer',
    ['questionID', 'userID', 'content', 'accepted', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
    1,
	6,
    'Try acting innocent',
	null,
    $now
]);

$this->db->execute([
    2,
    4,
    'I find that a good paint thinner does the trick.',
	null,
    $now
]);

$this->db->execute([
    2,
    2,
    'My best tip: don\'t. Nothing is ever really clean. Get rid of it.',
	$now,
    $now
]);