<?php
$this->db->dropTableIfExists('page')->execute();
	 
$this->db->createTable(
	'page',
	[
		'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
		'title' => ['varchar(80)', 'not null'],
		'content' => ['text', 'not null'],
		'slug' => ['varchar(80)', 'unique'],
		'filter' => ['varchar(80)'],
		'created' => ['datetime'],
		'updated' => ['datetime'],
		'deleted' => ['datetime'],
		'inactivated' => ['datetime'],
	]
)->execute();

 $this->db->insert(
	'page',
	['title', 'content', 'slug', 'filter', 'created']
);

$now = gmdate('Y-m-d H:i:s');

$this->db->execute([
	'A starter page from setup file',
	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas eros ut aliquam porta. Duis imperdiet pulvinar viverra. Curabitur molestie, erat non faucibus semper, turpis nulla vehicula sem, quis sodales erat eros eget diam. Pellentesque ac enim vitae sem rhoncus ultrices ac non lacus. Praesent id rhoncus lorem. Proin at leo eget elit imperdiet faucibus. Phasellus laoreet nec nisi non mollis. 
	
Duis nisl turpis, tempor et rutrum at, bibendum et mi. Morbi luctus libero id suscipit mollis. Vivamus id vehicula erat. Ut eu nisi ut nibh suscipit iaculis eget a tortor. Aliquam sodales consequat erat, tempus fermentum nulla tincidunt vitae. Praesent vestibulum tempus iaculis. Curabitur sagittis eros at nisi imperdiet, et semper arcu lobortis. Vestibulum eget ligula imperdiet, ultricies lectus convallis, placerat arcu. Maecenas ut ligula a magna dictum aliquam ut quis arcu. Etiam sed quam non sapien facilisis scelerisque. Aenean interdum, tellus eget pulvinar porttitor, lorem tellus laoreet neque, vitae mollis velit nunc sed odio. Ut posuere nunc id neque malesuada volutpat. Pellentesque eget augue sollicitudin, suscipit lectus ultricies, dictum elit. Morbi feugiat fermentum diam eget sodales. Mauris ut diam nec orci vestibulum imperdiet. Nam faucibus neque rutrum mi congue, vitae congue est tincidunt.',
	$this->page->slugify('A starter page'),
	'nl2br',
	$now
]);

$this->db->execute([
	'Trying some markdown',
	'###Lorem ipsum dolor sit amet
consectetur adipiscing elit. Nunc egestas eros ut aliquam porta. Duis imperdiet pulvinar viverra. Curabitur molestie, erat non faucibus semper, turpis nulla vehicula sem, quis sodales erat eros eget diam. **Pellentesque** ac enim vitae sem rhoncus ultrices ac non lacus. Praesent id rhoncus lorem. Proin at leo eget elit imperdiet faucibus. Phasellus laoreet nec nisi non mollis.

Duis nisl turpis, tempor et rutrum at, bibendum et mi. Morbi luctus libero id suscipit mollis. Vivamus id vehicula erat. Ut eu nisi ut nibh suscipit iaculis eget a tortor. Aliquam sodales consequat erat, tempus fermentum nulla tincidunt vitae. Praesent vestibulum tempus iaculis. Curabitur sagittis eros at nisi imperdiet, et semper arcu lobortis. Vestibulum eget ligula imperdiet, ultricies lectus convallis, placerat arcu. Maecenas ut ligula a magna dictum aliquam ut quis arcu. Etiam sed quam non sapien facilisis scelerisque. Aenean interdum, tellus eget pulvinar porttitor, lorem tellus laoreet neque, vitae mollis velit nunc sed odio. Ut posuere nunc id neque malesuada volutpat. Pellentesque eget augue sollicitudin, suscipit lectus ultricies, dictum elit. Morbi feugiat fermentum diam eget sodales. Mauris ut diam nec orci vestibulum imperdiet. Nam faucibus neque rutrum mi congue, vitae congue est tincidunt.',
	$this->page->slugify('Trying some markdown'),
	'markdown,nl2br',
	$now
]);