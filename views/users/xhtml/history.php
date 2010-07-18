<h2>Your Vote History</h2>

<p>You have voted <?php echo count($data['feed']);?> times.</p>

<ul>
    <?php foreach($data['feed'] as $vote):?>
    
        <li><a href="/topics/<?php echo $vote['handle'];?>" title="Opinions on the topic: <?php echo $vote['title'];?>"><?php echo $vote['title'];?></a>: <strong><?php echo $vote['opinion'];?></strong></li>
        
    <?php endforeach;?>
    
</ul>