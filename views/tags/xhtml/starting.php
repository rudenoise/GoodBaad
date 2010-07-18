<?php if (!isset($data['msg'])):?>
    
    <h2>Tags begingin with: <em><?php echo ucfirst($data['letter']); ?></em></h2>
    
    <p>
    <?php foreach(range('a','z') as $letter): ?>
    
        <?php if ($data['letter'] != $letter): ?><a href="/tags/<?php echo $letter; ?>" title="Tags starting with the letter <?php echo ucfirst($letter); ?>"><?php echo ucfirst($letter); ?></a>  <?php else: ?><strong><?php echo ucfirst($letter); ?></strong><?php endif; ?>
        
    <?php endforeach ?>
    </p>
    
    <div class="left">
        
        <ul>
            
    <?php $i = 1; foreach ($data['tags'] as $tag): ?>
        
            <li><a href="/tag/<?php echo $tag['title']; ?>"><?php echo $tag['title']; ?></a></li>
                
        <?php if ($i == $data['split']): ?>
            
        </ul>
        
    </div>
    
    <div class="right">
    
        <ul>
            
        <?php endif; ?>
                
            
        
    <?php $i++; endforeach; ?>
    
            </ul>
            
        </div>
    
<?php else:
if (!isset($data['letter'])) $data['letter'] = 1;
?>

    <h2><?php echo $data['msg']; ?></h2>
    
    <p>
    <?php foreach(range('a','z') as $letter): ?>
    
        <?php if ($data['letter'] != $letter): ?><a href="/tags/<?php echo $letter; ?>" title="Tags starting with the letter <?php echo ucfirst($letter); ?>"><?php echo ucfirst($letter); ?></a>  <?php else: ?><strong><?php echo ucfirst($letter); ?></strong><?php endif; ?>
        
    <?php endforeach ?>
    </p>
    
<?php endif; ?>