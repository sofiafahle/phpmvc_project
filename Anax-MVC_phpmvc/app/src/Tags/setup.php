<?php
$this->db->dropTableIfExists('tags')->execute();
	 
$this->db->createTable(
    'tags',
    [
        'id' 			=> ['integer', 'primary key', 'not null', 'auto_increment'],
		'title' 		=> ['varchar(20)', 'not null'],
		'userID' 		=> ['integer', 'not null'],
		'created'		=> ['datetime'],
		'updated'		=> ['datetime'],
		'deleted'		=> ['datetime'],
    ]
)->execute();

 $this->db->insert(
    'tags',
    ['title', 'userID', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute(['Murder', '1', $now]);
$this->db->execute(['Cleanup', '1', $now]);
$this->db->execute(['Lie detectors', '1', $now]);
$this->db->execute(['Dirty cops', '1', $now]);
$this->db->execute(['Framing', '1', $now]);



$this->db->dropTableIfExists('tag2question')->execute();
	 
$this->db->createTable(
    'tag2question',
    [
        'tagID' => ['integer', 'not null'],
		'questionID' => ['integer', 'not null'],
    ]
)->execute();

 $this->db->insert(
    'tag2question',
    ['tagID', 'questionID']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
    2,
	1
]);

$this->db->execute([
    1,
	1
]);