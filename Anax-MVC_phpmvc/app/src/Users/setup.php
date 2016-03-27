<?php

$this->db->dropTableIfExists('users')->execute();
	 
$this->db->createTable(
    'users',
    [
        'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'acronym' => ['varchar(20)', 'unique', 'not null'],
        'email' => ['varchar(80)', 'not null'],
        'name' => ['varchar(80)', 'not null'],
        'password' => ['varchar(255)', 'not null'],
        'web' => ['varchar(80)'],
		'about' => ['text'],
        'gravatar' => ['varchar(200)'],
        'created' => ['datetime'],
        'updated' => ['datetime'],
        'deleted' => ['datetime'],
        'inactivated' => ['datetime'],
    ]
)->execute();

 $this->db->insert(
    'users',
    ['acronym', 'email', 'name', 'password', 'about', 'gravatar', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
    'admin',
    'sofiafahlesson@live.se',
    'Administrator',
    password_hash('admin', PASSWORD_DEFAULT),
	'',
    'http://www.gravatar.com/avatar/' . md5(strtolower(trim("sofiafahlesson@live.se"))),
    $now
]);

$this->db->execute([
    'NanaWhite',
    'white@cluedo.com',
    'Mrs White',
    password_hash('white', PASSWORD_DEFAULT),
	'Loving Nana and splatter film afficionado.',
    $this->url->asset("img/gravatar/white.jpg"),
    $now
]);

$this->db->execute([
    'Col.Mustard',
    'mustard@cluedo.com',
    'Colonel Martin Mustard',
    password_hash('mustard', PASSWORD_DEFAULT),
	'Proud military man who hunts big game and plays big games.',
    $this->url->asset("img/gravatar/mustard.jpg"),
    $now
]);

$this->db->execute([
    'SweetScarlet87',
    'scarlet@cluedo.com',
    'Charlotte Scarlet',
    password_hash('scarlet', PASSWORD_DEFAULT),
	'Wouldn\'t you like to know? ;)',
    $this->url->asset("img/gravatar/scarlet.jpg"),
    $now
]);

$this->db->execute([
    'PlumpProfessor',
    'plum@cluedo.com',
    'Prof. Arnold Plum ',
    password_hash('plum', PASSWORD_DEFAULT),
	'Intellectual who is seeking the proper funding for a **great** expedition to Egypt.',
    $this->url->asset("img/gravatar/plum.jpg"),
    $now
]);

$this->db->execute([
    'GreenforJesus',
    'green@cluedo.com',
    'Reverend John Green',
    password_hash('green', PASSWORD_DEFAULT),
	'A vessel for the Lord on Earth. *Romans 12:19 Dearly beloved, avenge not yourselves, but rather give place to wrath: for it is written, Vengeance is mine; I will repay, said the Lord.*',
    $this->url->asset("img/gravatar/green.jpg"),
    $now
]);

$this->db->execute([
    'BlueFeathers',
    'peacock@cluedo.com',
    'Mrs Annabelle Peacock',
    password_hash('peacock', PASSWORD_DEFAULT),
	'Widower with a spark, well versed in politics and always knows what goes on behind closed doors.',
    $this->url->asset("img/gravatar/peacock.jpg"),
    $now
]);