<?php
$this->db->dropTableIfExists('question')->execute();
	 
$this->db->createTable(
    'question',
    [
        'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'userID' => ['varchar(20)', 'not null'],
        'title' => ['varchar(80)', 'not null'],
        'content' => ['text', 'not null'],
		'answered' => ['datetime'],
        'created' => ['datetime'],
        'updated' => ['datetime'],
        'deleted' => ['datetime'],
        'inactivated' => ['datetime']
    ]
)->execute();

 $this->db->insert(
    'question',
    ['userID', 'title', 'content', 'answered', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
    2,
    'How do I hide my involvation in the murder my boss?',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas eros ut aliquam porta. Duis imperdiet pulvinar viverra. Curabitur molestie, erat non faucibus semper, turpis nulla vehicula sem, quis sodales erat eros eget diam. Pellentesque ac enim vitae sem rhoncus ultrices ac non lacus. Praesent id rhoncus lorem. Proin at leo eget elit imperdiet faucibus. Phasellus laoreet nec nisi non mollis.',
	null,
    $now
]);

$this->db->execute([
    3,
    'The best way to clean a bloody wrench?',
    '####Lorem ipsum dolor sit amet
consectetur adipiscing elit. Nunc egestas eros ut aliquam porta. Duis imperdiet pulvinar viverra. Curabitur molestie, erat non faucibus semper, turpis nulla vehicula sem, quis sodales erat eros eget diam. **Pellentesque** ac enim vitae sem rhoncus ultrices ac non lacus. Praesent id rhoncus lorem. Proin at leo eget elit imperdiet faucibus. Phasellus laoreet nec nisi non mollis.',
	$now,
    $now
]);
