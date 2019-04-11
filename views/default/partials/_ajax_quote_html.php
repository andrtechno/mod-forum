<blockquote>
    <cite><?=$post->user->login?> сказал(а) <?=\panix\engine\CMS::date($post->created_at,true)?>:</cite>
    <div><p><?= $post->text?></p></div>
</blockquote>
<br/>