<?php if(isset($text)) : ?>
	<h1><?= $text ?></h1>
<?php endif; ?>
 
<?php foreach ($questions as $question) : ?>

<h3><strong><?= $question->id ?>. <a href='<?=$this->url->create('question/view/' . $question->id)?>'><?= $question->title ?></a></strong></h3>
<p>User: <?= $question->user->email ?><br>
  Posted: <?= $question->created ?>
      <?= $question->updated ? '<br><font color="#01f">Updated: ' . $question->updated . '</font>' : null ?>
      <?= $question->inactivated ? '<br><font color="#fa0">Inactivated: ' . $question->inactivated . '</font>' : null ?>
      <?= $question->deleted ? '<br><font color="#f00">Deleted: ' . $question->deleted . '</font>' : null ?><br>
</p>

<?php if ($this->session->get('user') == 1) : ?>
    <br>
    <p>
    <?php if ($question->deleted) : ?>
        <a href='<?=$this->url->create('question/restore/' . $question->id)?>'>Restore</a> | 
        <a href='<?=$this->url->create('question/delete/' . $question->id)?>'>Delete fully</a> |
    <?php else : ?>
        <a href='<?=$this->url->create('question/soft-delete/' . $question->id)?>'>Delete</a> |
        
        <?php if ($question->inactivated) : ?>
            <a href='<?=$this->url->create('question/activate/' . $question->id)?>'>Activate</a> | 
        <?php else : ?>
            <a href='<?=$this->url->create('question/inactivate/' . $question->id)?>'>Inactivate</a> |
        <?php endif; ?>
        
    <?php endif; ?>
    
    <a href='<?=$this->url->create('question/update/' . $question->id)?>'>Edit</a></p>
<?php endif; ?>
 
<?php endforeach; ?>

<?php if ($this->session->get('user') == 1) : ?>
    <br>
    <p><a href='<?=$this->url->create('question/list')?>'>All questions</a> | <a href='<?=$this->url->create('question/active')?>'>Active questions</a> | <a href='<?=$this->url->create('question/inactive')?>'>Inactive questions</a> | <a href='<?=$this->url->create('question/trash')?>'>Deleted questions</a> | <a href='<?=$this->url->create('question/setup')?>'>Reset table</a></p>
<?php endif; ?>