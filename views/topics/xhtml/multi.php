<h2>Which '<em><?php echo $data['topics'][0]['title'];?></em>' Were You Looking For?</h2>

<?php foreach ($data['topics'] as $topic):?>
    
    <h3><a href="/topics/<?php echo $topic['guid'];?>"><?php echo $topic['title'];?></a></h3>
    
    <p>
        <?php $n = count($topic['tags']); $i = 1;?>
        
        <?php foreach ($topic['tags'] as $tag): ?>
        
            <?php  if ($i != $n): echo ucfirst($tag['title']) . ', '; $i++; else: echo ucfirst($tag['title']) . '.'; endif;?>
        
        <?php endforeach; ?>
    </p>
    
<?php endforeach;?>