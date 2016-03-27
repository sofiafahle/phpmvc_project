<div class="answer clearfix" id="answer-<?= $answer->id ?>">
    <?php if (isset($answer->number)) : ?>
    	<h2>Answer #<?= $answer->number ?><?= $answer->accepted != null ? ' (Accepted answer)' : '' ?></h2>
    <?php endif; ?>
    <span class="post-info">Posted: <?= $answer->created ?></span>
    
    <p><?= $this->textFilter->doFilter($answer->content, 'markdown') ?></p>
    
    <div class="user-info">
        <img class="gravatar gravatar-small" src="<?= $answer->user->gravatar ?>"/>
        <p>Posted by <a href="<?= $this->url->create('users/id/' . $answer->user->id) ?>"><?= $answer->user->acronym ?></a></p>
        <span class="comment-time">
            <?= $answer->created ?>
            <?= $answer->updated ? ' (updated: ' . $answer->updated . ')' : '' ?>
            <?= $answer->deleted ? ' (deleted: ' . $answer->deleted . ')' : '' ?>
        </span>
    </div>
    
    <?php if ($this->session->get('user') == $answer->user->id || $this->session->get('user') == 1) : ?>
        <form class="answer-buttons" method=post>
            <input type='submit' name='doUpdate' value='Update' onClick="this.form.action = '<?= $this->url->create('answer/update/' . $answer->id) ?>'"/>
            <?php if ($answer->deleted) : ?>
                <input type='submit' name='doRestore' value='Restore' onClick="this.form.action = '<?=$this->url->create('answer/restore/' . $answer->id)?>'"/>
                <input type='submit' name='doDelete' value='Delete fully' onClick="this.form.action = '<?=$this->url->create('answer/delete/' . $answer->id)?>'"/>
            <?php else : ?>
                <input type='submit' name='doSoftDelete' value='Delete' onClick="this.form.action = '<?=$this->url->create('answer/soft-delete/' . $answer->id)?>'"/>
                
                <?php if ($answer->inactivated) : ?>
                    <input type='submit' name='doActivate' value='Activate' onClick="this.form.action = '<?=$this->url->create('answer/activate/' . $answer->id)?>'"/>
                <?php else : ?>
                    <input type='submit' name='doInactivate' value='Inactivate' onClick="this.form.action = '<?=$this->url->create('answer/inactivate/' . $answer->id)?>'"/>
                <?php endif; ?>
                
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>