<div class="question clearfix">
	<h1><?= $question->title ?> <?php if ($question->inactivated !== null) { echo '(Inactive)'; } ?></h1>
    <p><?= $this->textFilter->doFilter($question->content, 'markdown') ?></p>
    
    <div class="user-info">
        <img class="gravatar gravatar-medium" src="<?= $question->user->gravatar ?>"/>
        <p>Posted by  <a href="<?= $this->url->create('users/id/' . $question->user->id) ?>"><?= $question->user->acronym ?></a></p> 
        <span class="comment-time">
            <?= $question->created ?>
            <?= $question->updated ? ' (updated: ' . $question->updated . ')' : '' ?>
            <?= $question->deleted ? ' (deleted: ' . $question->deleted . ')' : '' ?>
        </span>
    </div>
    
    <?php if ($this->session->get('user') == $question->user->id || $this->session->get('user') == 1) : ?>
        <form class="question-buttons" method=post>
            <input type='submit' name='doUpdate' value='Update' onClick="this.form.action = '<?= $this->url->create('question/update/' . $question->id) ?>'"/>
            <?php if ($question->deleted) : ?>
                <input type='submit' name='doRestore' value='Restore' onClick="this.form.action = '<?=$this->url->create('question/restore/' . $question->id)?>'"/>
                <input type='submit' name='doDelete' value='Delete fully' onClick="this.form.action = '<?=$this->url->create('question/delete/' . $question->id)?>'"/>
            <?php else : ?>
                <input type='submit' name='doSoftDelete' value='Delete' onClick="this.form.action = '<?=$this->url->create('question/soft-delete/' . $question->id)?>'"/>
                
                <?php if ($question->inactivated) : ?>
                    <input type='submit' name='doActivate' value='Activate' onClick="this.form.action = '<?=$this->url->create('question/activate/' . $question->id)?>'"/>
                <?php else : ?>
                    <input type='submit' name='doInactivate' value='Inactivate' onClick="this.form.action = '<?=$this->url->create('question/inactivate/' . $question->id)?>'"/>
                <?php endif; ?>
                
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>