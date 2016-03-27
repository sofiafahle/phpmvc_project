<h1><?=$title?><?= $user->inactivated ? ' (Inactive)' : null ?></h1>
<img class="gravatar gravatar-large" src="<?= $user->gravatar ?>"/>

<p> Name: <span class="user-name"><?= $user->name ?></span><br>
Email: <?= $user->email ?><br>
Joined: <?= $user->created ?><br>
<?php if ($user->web) : ?>
	Web: <?= $user->web ?><br>
<?php endif; ?>
<?php if ($user->about) : ?>
	About: <?= $this->textFilter->doFilter($user->about, 'markdown') ?>
<?php endif; ?>
</p>
<br>


<?php if ($user->posts['questions'] != null) : ?>
<p>Posted questions:<ul>
	<?php foreach ($user->posts['questions'] as $question) : ?>
    	<?php if ($question->deleted == null) : ?>
        	<li><a href="<?= $this->url->create('question/view/' . $question->id) ?>"><?= $question->title ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>

<?php if ($user->posts['answers'] != null) : ?>
<p>Answered questions:<ul>
	<?php foreach ($user->posts['answers'] as $answer) : ?>
    	<?php if ($answer->deleted == null) : ?>
        	<li><a href="<?= $this->url->create('question/view/' . $answer->question->id . '?#answer-' . $answer->id) ?>"><?= $answer->question->title ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>

<?php if ($user->posts['comments'] != null) : ?>
<p>Comments made:<ul>
	<?php foreach ($user->posts['comments'] as $comment) : ?>
		<?php if ($comment->deleted == null) : ?>
            <li><a href="<?= $this->url->create('question/view/' . $comment->question->id . '?#comment-' . $comment->id) ?>"><?= $comment->question->title ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul></p>
<?php endif; ?>

<p>
<?php if ($this->userInfo['id'] == $user->id || isset($this->userInfo['admin'])) : ?>
    <a href="<?= $this->url->create('user/update/' . $user->id) ?>">Edit profile</a> | 
<?php endif; ?>
 
<a href='<?=$this->url->create('users/list')?>'>All users</a></p>
