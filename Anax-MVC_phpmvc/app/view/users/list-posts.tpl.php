<?php if ($user->posts['questions'] != null) : ?>
<p>Posted questions:<ul>
	<?php foreach ($user->posts['questions'] as $question) : ?>
        	<li>
            	<a href="<?= $this->url->create('question/view/' . $question->id) ?>"><?= $question->title ?></a>
                
                <?php if($question->deleted != null) : ?>
                	(deleted)&nbsp;|&nbsp;<a href="<?= $this->url->create('question/restore/' . $question->id) ?>">Restore</a>
                <?php endif; ?>
                <?php if($question->inactivated != null) : ?>
                	(inactivated)&nbsp;|&nbsp;<a href="<?= $this->url->create('question/activate/' . $question->id) ?>">Activate</a>
                <?php endif; ?>
            </li>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>

<?php if ($user->posts['answers'] != null) : ?>
<p>Answered questions:<ul>
	<?php foreach ($user->posts['answers'] as $answer) : ?>
        	<li>
            	<a href="<?= $this->url->create('question/view/' . $answer->question->id . '?#answer-' . $answer->id) ?>"><?= $answer->question->title ?></a>
                
                <?php if($answer->deleted != null) : ?>
                	(deleted)&nbsp;|&nbsp;<a href="<?= $this->url->create('answer/restore/' . $answer->id) ?>">Restore</a>
                <?php endif; ?>
                <?php if($answer->inactivated != null) : ?>
                	(inactivated)&nbsp;|&nbsp;<a href="<?= $this->url->create('answer/activate/' . $answer->id) ?>">Activate</a>
                <?php endif; ?>
            </li>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>

<?php if ($user->posts['comments'] != null) : ?>
<p>Comments made:<ul>
	<?php foreach ($user->posts['comments'] as $comment) : ?>
            <li>
            	<a href="<?= $this->url->create('question/view/' . $comment->question->id . '?#comment-' . $comment->id) ?>"><?= $comment->question->title ?></a>
                
                <?php if($comment->deleted != null) : ?>
                	(deleted)&nbsp;|&nbsp;<a href="<?= $this->url->create('comment/restore/' . $comment->id) ?>">Restore</a>
                <?php endif; ?>
            </li>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>