<?php if (!isset($data['msg'])):?>
    
    <h2><?php echo $data['title']; ?></h2>
    
    <p>Find other <a href="/tags/" title="find other topic tags">topic tags</a></p>
    
        <div class="smallFive">
            
            <h3>Most Voted</h3>
            <ol>
                <?php foreach ($data['mostVoted'] as $topic): ?>
                    <?php if ($topic['total'] > 0): ?><li><a href="/topics/<?php echo $topic['handle']; ?>" title="Opinions on the topic: <?php echo $topic['title']; ?>"><?php echo $topic['title']; ?></a> (<?php echo $topic['total']; ?>)</li><?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
        
        <div class="smallFive">
            
            <h3>Most Good</h3>
            <ol>
                <?php foreach ($data['mostGood'] as $topic): ?>
                    <?php if ($topic['good'] > 0): ?><li><a href="/topics/<?php echo $topic['handle']; ?>" title="Opinions on the topic: <?php echo $topic['title']; ?>"><?php echo $topic['title']; ?></a> (<?php echo $topic['good']; ?>)</li><?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
        
        <div class="smallFive">
            
            <h3>Most Baad</h3>
            <ol>
                <?php foreach ($data['mostBaad'] as $topic): ?>
                    <?php if ($topic['baad'] > 0): ?><li><a href="/topics/<?php echo $topic['handle']; ?>" title="Opinions on the topic: <?php echo $topic['title']; ?>"><?php echo $topic['title']; ?></a> (<?php echo $topic['baad']; ?>)</li><?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
        
        <div class="clear"></div>
        
    
    <div>
        <h3>All Topics Tagged <em><?php echo $data['tag']; ?></em></h3>
        
        <ul>
            <?php foreach ($data['allTopics'] as $topic): ?>
                <li><a href="/topics/<?php echo $topic['handle']; ?>" title="Opinions on the topic: <?php echo $topic['title']; ?>"><?php echo $topic['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
<?php else: ?>

    <h2><?php echo $data['msg'];?></h2>
    
    <p>Find other <a href="/tags/" title="find other topic tags">topic tags</a></p>
    
<?php endif; ?>