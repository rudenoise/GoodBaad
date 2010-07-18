<?php if (!isset($data['msg'])):?>

    
    <h2><?php echo ucfirst($data['user']['username']);?>'s Opinions Page</h2>
    
    <p class="clear">
        <a href="/users/follow/<?php echo $data['user']['username'];?>"
            class="goodbtn" title="Follow: <?php echo ucfirst($data['user']['username']);?>'s opinions">
            <span>Follow <?php echo ucfirst($data['user']['username']);?></span>
        </a>
    </p>
    
    <div class="left">
        
        <?php if (isset($data['user']['totals'])):?>
        
            <h3><?php echo ucfirst($data['user']['username']);?>'s Overall Mood</h3>
                
            <?php if (isset($data['user']['percentages'])):?>
                
                <p><?php echo $data['user']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['user']['percentages']['baad']?>% <strong>Baad</strong> from their last <?php echo ($data['user']['totals']['good']+$data['user']['totals']['baad']);?> opinions.</p>
                
                <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['user']['percentages']['baad'];?>,<?php echo $data['user']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Overall Mood Pie Chart" />
                
            <?php else:?>
                
                <p>This user has not voted on any topics yet.</p>
            
            <?php endif;?>
            
        <?php endif;?>
        
    </div>
    
    <div class="right">
        
        <?php if (isset($data['following'])):?>
        
            <h3><?php echo ucfirst($data['user']['username']);?> is followed by <?php echo $data['following']['total'];?> users.</h3>
            
            <p>
                <?php foreach($data['following']['users'] as $user):?>
                
                    <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                    
                <?php endforeach;?>
            </p>
            
        <?php endif;?>
        
        <?php if (isset($data['followedBy'])):?>
        
            <h3><?php echo ucfirst($data['user']['username']);?> is following <?php echo $data['followedBy']['total'];?> users.</h3>
            
            <p>
                <?php foreach($data['followedBy']['users'] as $user):?>
                
                    <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                    
                <?php endforeach;?>
            </p>
            
        <?php endif;?>
        
        <?php if (isset($data['userFeed'])):?>
            
            <h3>Recent Votes From <?php echo ucfirst($data['user']['username']);?>'s Friends</h3>
                
            <ul>
                <?php foreach($data['userFeed'] as $opinion):?>
                
                <li class="votable" id="<?php echo $opinion['guid'];?>"><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst( $opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
                
                <?php endforeach;?>
            </ul>
            
        <?php endif;?>
        
    </div>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" ></script>

    <script type="text/javascript">
        
        $(document).ready(function()
        {
            $(".votable").hover(
                function ()
                {
                    
                    var curr = $(this).attr("id");
                    
                    $(this).append($("<p class=\"clear\"><a href=\"/votes/cast/" + curr + "/good\" class=\"goodbtn\"><span>Good</span></a><a href=\"/votes/cast/" + curr + "/baad\" class=\"baadbtn\" ><span>Baad</span></a></p>"));
                }, 
                function ()
                {
                    $(this).find("p:last").remove();
                }
            );
    
        });
    
    </script>
    
<?php else:?>

    <h2><?php echo $data['msg'];?></h2>
    
<?php endif;?>