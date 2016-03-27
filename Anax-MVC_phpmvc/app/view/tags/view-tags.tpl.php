<?php if (isset($text)) { echo $text; } ?>

<?php foreach($tags as $tag) : ?>
<a class="tag" href="<?= $this->url->create('tags/view/' . $tag->id) ?>"><?= $tag->title ?></a>
<?php endforeach; ?>