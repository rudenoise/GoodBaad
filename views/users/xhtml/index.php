<div class="left">
    
    <?php if (isset($data['grandTotal'])):?>
    
        <h3>The World is Currently <em><?php echo $data['grandTotal']['percentages']['good']?>% Good, <?php echo $data['grandTotal']['percentages']['baad']?>% Baad</em></h3>
        
        <p>According to the <a href="/worldometer" title="Updates on the state of the world">Good Baad Worldometer</a>.</p>
        
        <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['grandTotal']['percentages']['baad'];?>,<?php echo $data['grandTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Overall Mood Pie Chart" />
        
    <?php endif;?>
    
    <?php if (isset($data['masterFeed'])):?>
        
        <h3>Recent Votes From Good Baad Users</h3>
            
        <ul>
            <?php foreach($data['masterFeed'] as $opinion):?>
            
            <li class="votable" id="<?php echo $opinion['guid'];?>"><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst($opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
            
            <?php endforeach;?>
        </ul>
    
    <?php endif;?>
    
</div>

<div class="right">
    
    <?php if (isset($_SESSION['userName'])):?>
    
        <?php if (isset($data['followedByGrandTotal'])):?>
            
            <h3>Overall Mood of Your Friends</h3>
                
            <?php if (isset($data['followedByGrandTotal']['percentages'])):?>
            
                <p><?php echo $data['followedByGrandTotal']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['followedByGrandTotal']['percentages']['baad']?>% <strong>Baad</strong>.</p>
                
                <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['followedByGrandTotal']['percentages']['baad'];?>,<?php echo $data['followedByGrandTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="A pie chart showing the overall opinion of users you are following." />
                
            <?php else:?>
            
                <p>The users you are following have not voted on any topics yet.</p>
                
            <?php endif;?>
            
        <?php endif;?>
        
        <?php if (isset($data['followedByFeed'])):?>
            
            <h3>Recent Votes From Your Friends</h3>
                
            <ul>
                <?php foreach($data['followedByFeed'] as $opinion):?>
                
                <li class="votable" id="<?php echo $opinion['guid'];?>"><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst($opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
                
                <?php endforeach;?>
            </ul>
            
        <?php endif;?>
        
        <?php if (isset($data['followedBy'])):?>
            
            <h3>Users you are following:</h3>
            
            <p>
                <?php foreach($data['followedBy'] as $user):?>
                    <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                <?php endforeach;?>
            </p>
        
        <?php else:?>
            
            <p class="intro">You Are not following any user's opinions yet...</p>
            
        <?php endif;?>
        
    <?php else:?>
    
        <p class="intro">Your are not logged in, you can <a href="/login" title="Log In to Good Baad">Log In</a> or <a href="/signup" title="Sign up to Good Baad">Sign Up</a> up to post your own opinions and see what your friends have been up to.</p>
        
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
