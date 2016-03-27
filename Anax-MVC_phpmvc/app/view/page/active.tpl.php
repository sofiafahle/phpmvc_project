<h1><?=$title?></h1>

<?php foreach ($pages as $page) : ?>

	<h3><strong><?= $page->id ?>. <a href='<?=$this->url->create('page/view/' . $page->slug)?>'><?= $page->title ?></a></strong></h3>
    <p> Created: <?= $page->created ?>
      <?= $page->updated ? '<br><font color="#01f">Updated: ' . $page->updated . '</font>' : null ?>
      <?= $page->inactivated ? '<br><font color="#fa0">Inactivated: ' . $page->inactivated . '</font>' : null ?>
      <?= $page->deleted ? '<br><font color="#f00">Deleted: ' . $page->deleted . '</font>' : null ?><br>
      Filter: <?= $page->filter ?><br>
    </p>
    
    <p><a href='<?=$this->url->create('page/inactivate/' . $page->id)?>'>Inactivate</a> | <a href='<?=$this->url->create('page/soft-delete/' . $page->id)?>'>Delete</a> | <a href='<?=$this->url->create('page/update/' . $page->id)?>'>Edit</a></p>

<?php endforeach; ?>
 
<br>
<p><a href='<?=$this->url->create('page/list')?>'>All pages</a>