<?php if (is_array($comments)) : ?>
	<div class='comments'>
    	<h4 id="comments">Comments</h4>
        <?php foreach ($comments as $id => &$comment) : ?>
        	<?php if($comment->deleted == null) : ?>
                <div class="comment clearfix" id="comment-<?= $comment->id ?>">
                    <div class="comment-content">
                        <?php if(isset($comment->number)) : ?>
                            <h4 class="comment-title">#<?= $comment->number ?></h4>
                        <?php else : ?>
                            <h4 class="comment-title">#<?= $comment->id ?></h4>
                        <?php endif; ?>
                        <a href="<?=$this->url->create('users/id/' . $comment->user->id) ?>" class="comment-user"><?= $comment->user->acronym ?></a> (<?= $comment->user->email ?>)
                        <span class="comment-time">
                            <?= $comment->created ?>
                            <?= $comment->updated ? ' (updated: ' . $comment->updated . ')' : '' ?>
                            <?= $comment->deleted ? ' (deleted: ' . $comment->deleted . ')' : '' ?>
                        </span>
                        <p class="comment-text"><?= $this->textFilter->doFilter($comment->content, 'markdown') ?></p>
                    </div>
                    
                    <?php if ($this->session->get('user') == $comment->user->id || $this->session->get('user') == 1) : ?>
                        <form class="comment-buttons" method=post>
                            <input type='submit' name='doUpdate' value='Update' onClick="this.form.action = '<?= $this->url->create('comment/update/' . $comment->id) ?>'"/>
                            <?php if ($comment->deleted) : ?>
                                <input type='submit' name='doRestore' value='Restore' onClick="this.form.action = '<?= $this->url->create('comment/restore/' . $comment->id) ?>'"/>
                                <input type='submit' name='doRemove' value='Delete fully' onClick="this.form.action = '<?= $this->url->create('comment/delete/' . $comment->id) ?>'"/>
                            <?php else : ?>
                                <input type='submit' name='doRemove' value='Remove' onClick="this.form.action = '<?= $this->url->create('comment/softdelete/' . $comment->id) ?>'"/>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>