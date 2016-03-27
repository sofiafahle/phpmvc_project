<?php if(isset($text)) : ?>
	<h1><?= $text ?></h1>
<?php endif; ?>
 
<?php foreach ($answers as $answer) : ?>

<h3><strong><?= $answer->id ?>. Answer to <a href="<?= $this->url->create('question/view/' . $answer->question->id) ?>"><?= $answer->question->title ?></a></strong></h3>
<p>Content: <?= strip_tags(htmlentities(mb_substr($answer->content, 0, 200), null, 'UTF-8')) ?> ..</p>
<p>User: <?= $answer->user->email ?><br>
  Posted: <?= $answer->created ?>
      <?= $answer->updated ? '<br><font color="#01f">Updated: ' . $answer->updated . '</font>' : null ?>
      <?= $answer->inactivated ? '<br><font color="#fa0">Inactivated: ' . $answer->inactivated . '</font>' : null ?>
      <?= $answer->deleted ? '<br><font color="#f00">Deleted: ' . $answer->deleted . '</font>' : null ?><br>
</p>

<a href="<?= $this->url->create('answer/id/' . $answer->id) ?>">See comments</a>

<?php if ($this->userInfo['admin']) : ?>
    <br>
    <p>
    <?php if ($answer->deleted) : ?>
        <a href='<?=$this->url->create('answer/restore/' . $answer->id)?>'>Restore</a> | 
        <a href='<?=$this->url->create('answer/delete/' . $answer->id)?>'>Delete fully</a> |
    <?php else : ?>
        <a href='<?=$this->url->create('answer/soft-delete/' . $answer->id)?>'>Delete</a> |
        
        <?php if ($answer->inactivated) : ?>
            <a href='<?=$this->url->create('answer/activate/' . $answer->id)?>'>Activate</a> | 
        <?php else : ?>
            <a href='<?=$this->url->create('answer/inactivate/' . $answer->id)?>'>Inactivate</a> |
        <?php endif; ?>
        
    <?php endif; ?>
    
    <a href='<?=$this->url->create('answer/update/' . $answer->id)?>'>Edit</a></p>
<?php endif; ?>
 
<?php endforeach; ?>

<?php if ($this->session->get('user') == 1) : ?>
    <br>
    <p><a href='<?=$this->url->create('answer/list')?>'>All answers</a> | <a href='<?=$this->url->create('answer/active')?>'>Active answers</a> | <a href='<?=$this->url->create('answer/inactive')?>'>Inactive answers</a> | <a href='<?=$this->url->create('answer/trash')?>'>Deleted answers</a> | <a href='<?=$this->url->create('answer/setup')?>'>Reset table</a></p>
<?php endif; ?>