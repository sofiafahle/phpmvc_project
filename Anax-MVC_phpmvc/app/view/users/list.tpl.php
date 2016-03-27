<h1><?=$title?></h1>
 
<?php foreach ($users as $user) : ?>

<h3><strong><?= $user->id ?>. <a href='<?=$this->url->create('users/id/' . $user->id)?>'><?= $user->acronym ?></a></strong></h3>
<p>Email: <?= $user->email ?><br>
  Joined: <?= $user->created ?>
      <?= $user->updated ? '<br><font color="#01f">Updated: ' . $user->updated . '</font>' : null ?>
      <?= $user->inactivated ? '<br><font color="#fa0">Inactivated: ' . $user->inactivated . '</font>' : null ?>
      <?= $user->deleted ? '<br><font color="#f00">Deleted: ' . $user->deleted . '</font>' : null ?><br>
</p>

<?php if (isset($this->userInfo['admin'])) : ?>
    <br>
    <p>
    <?php if ($user->deleted) : ?>
        <a href='<?=$this->url->create('users/restore/' . $user->id)?>'>Restore</a> | 
        <a href='<?=$this->url->create('users/delete/' . $user->id)?>'>Delete fully</a> |
    <?php else : ?>
        <a href='<?=$this->url->create('users/soft-delete/' . $user->id)?>'>Delete</a> |
        
        <?php if ($user->inactivated) : ?>
            <a href='<?=$this->url->create('users/activate/' . $user->id)?>'>Activate</a> | 
        <?php else : ?>
            <a href='<?=$this->url->create('users/inactivate/' . $user->id)?>'>Inactivate</a> |
        <?php endif; ?>
        
    <?php endif; ?>
    
    <a href='<?=$this->url->create('users/update/' . $user->id)?>'>Edit</a></p>
<?php endif; ?>
 
<?php endforeach; ?>

<?php if (isset($this->userInfo['admin'])) : ?>
    <br>
    <p><a href='<?=$this->url->create('users/list')?>'>All users</a> | <a href='<?=$this->url->create('users/active')?>'>Active users</a> | <a href='<?=$this->url->create('users/inactive')?>'>Inactive users</a> | <a href='<?=$this->url->create('users/trash')?>'>Deleted users</a> | <a href='<?=$this->url->create('users/setup')?>'>Reset table</a></p>
<?php endif; ?>