<?php if (isset($text)) { echo $text; } ?>

<?php foreach ($users as $user) : ?>

<h4><strong><?= $user->id ?>. <a href='<?=$this->url->create('users/id/' . $user->id)?>'><?= $user->acronym ?></a></strong></h4>
<p>Email: <?= $user->email ?><br>
  Joined: <?= $user->created ?>
      <?= $user->updated ? '<br><font color="#01f">Updated: ' . $user->updated . '</font>' : null ?>
      <?= $user->inactivated ? '<br><font color="#fa0">Inactivated: ' . $user->inactivated . '</font>' : null ?>
      <?= $user->deleted ? '<br><font color="#f00">Deleted: ' . $user->deleted . '</font>' : null ?><br>
  <?= isset($user->postCount) ? 'Posts: ' . $user->postCount : '' ?>
</p>

<?php endforeach; ?>
<br>
<a href='<?=$this->url->create('users/list')?>'>All users</a>
<br>