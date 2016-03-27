<?php if(isset($text)) : ?>
	<h1><?= $text ?></h1>
<?php endif; ?>
 
<?php foreach ($tags as $tag) : ?>

    <h3><strong><?= $tag->id ?>. <a href='<?=$this->url->create('tags/view/' . $tag->id)?>'><?= $tag->title ?></a></strong></h3>
    <p>Added by: <a href='<?=$this->url->create('users/id/' . $tag->user->id)?>'><?= $tag->user->acronym ?></a><br>
      Added: <?= $tag->created ?>
          <?= $tag->updated ? '<br><font color="#01f">Updated: ' . $tag->updated . '</font>' : null ?>
          <?= $tag->deleted ? '<br><font color="#f00">Deleted: ' . $tag->deleted . '</font>' : null ?><br>
    </p>
   
        <?php if ($tag->deleted) : ?>
            <a href='<?=$this->url->create('tags/restore/' . $tag->id)?>'>Restore</a> | 
            <a href='<?=$this->url->create('tags/delete/' . $tag->id)?>'>Delete fully</a> |
        <?php else : ?>
            <a href='<?=$this->url->create('tags/soft-delete/' . $tag->id)?>'>Delete</a> |
            
        <?php endif; ?>
        
        <a href='<?=$this->url->create('tags/update/' . $tag->id)?>'>Edit</a></p>

<?php endforeach ?>