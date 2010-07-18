<h2>Find Users and Topic Stats:</h2>

<form name="Search" action="" method="get">
    
    <ul id="find">
        
        <li><input type="text" name="string" value="<?php if (isset($data['string'])): echo $data['string']; endif;?>" /></li>
        
        <li>
            <select name="option">
                
                <option value="topic" <?php if (isset($data['option'])) if ($data['option'] == 'topic') echo 'selected';?>>topic</option>
                
                <option value="user" <?php if (isset($data['option'])) if ($data['option'] == 'user') echo 'selected';?>>user</option>
                
            </select>
        </li>
        
        <li><input type="submit" value="Go" /></li>
        
    </ul>
    
</form>

<?php if (isset($data['msg'])):?><p class="clear"><?php echo $data['msg'];?></p><?php endif;?>

<?php if (isset($data['results'])):?>
    
    <?php if (isset($data['results']['topic'])):?>
    
        <?php if (isset($data['results']['topic']['exact'][0])):?>
            
            <h3>Topics matching your search:</h3>
            
            <ul>
                <?php foreach ($data['results']['topic']['exact'] as $exact):?>
                    
                    <li><a href="/topics/<?php echo $exact['handle'];?>" title="<?php echo $exact['title'];?>"><?php echo $exact['title'];?></a></li>
                    
                <?php endforeach;?>
            </ul>
            
        <?php else:?>
        
            <p class="clear">No exact matches for your search.</p>
            
        <?php endif;?>
        
        <?php if (isset($data['results']['topic']['like'][0])):?>
            
            <h3>Topics similar to your search:</h3>
            
            <ul>
                <?php foreach ($data['results']['topic']['like'] as $like):?>
                    
                    <li><a href="/topics/<?php echo $like['handle'];?>" title="<?php echo $like['title'];?>"><?php echo $like['title'];?></a></li>
                    
                <?php endforeach;?>
            </ul>
            
        <?php else:?>
        
            <p class="clear">No similar matches for your search.</p>
            
        <?php endif;?>
    
    <?php endif;?>
    
    <?php if (isset($data['results']['user'])):?>
    
        <?php if (isset($data['results']['user']['exact'][0])):?>
            
            <h3>Users matching your search:</h3>
            
            <ul>
                <?php foreach ($data['results']['user']['exact'] as $exact):?>
                    
                    <li><a href="/users/<?php echo $exact['username'];?>" title="<?php echo $exact['username'];?>"><?php echo $exact['username'];?></a></li>
                    
                <?php endforeach;?>
            </ul>
            
        <?php endif;?>
        
        <?php if (isset($data['results']['user']['like'][0])):?>
            
            <h3>Users similar to your search:</h3>
            
            <ul>
                <?php foreach ($data['results']['user']['like'] as $like):?>
                    
                    <li><a href="/users/<?php echo $like['username'];?>" title="<?php echo $like['username'];?>"><?php echo $like['username'];?></a></li>
                    
                <?php endforeach;?>
            </ul>
            
        <?php endif;?>
    
    <?php endif;?>

<?php endif;?>