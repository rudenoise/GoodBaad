<?php if (!isset($data['msg'])):?>
    
    <div class="left">
        
        <h2>Topic: <?php echo $data['topic'][0]['title'];?></h2>
    
        <p>
            Read more about
            <a href="http://www.freebase.com/view<?php echo $data['topic'][0]['freebase_id'];?>"
                title="Read more about <?php echo $data['topic'][0]['title'];?>" target="blank">
                <?php echo $data['topic'][0]['title'];?> on Freebase
            </a>.
        </p>
    
    </div>
    
    <div class="right">
        
        <?php if (isset($data['lastVote'])):?>
        
            <p>
                You voted <strong><?php echo $data['topic'][0]['title'];?></strong>: <strong><?php echo $data['lastVote']['opinion'];?></strong>.
                Changed your mind? Vote:
            </p>
            <p>
                <a href="/votes/cast/<?php echo $data['topic'][0]['guid'];?>/<?php if ($data['lastVote']['opinion'] == 'good') echo 'baad'; else echo 'good';?>" class="<?php if ($data['lastVote']['opinion'] == 'good') echo 'baad'; else echo 'good';?>btn" title="Vote: <?php echo $data['topic'][0]['title'];?> <?php if ($data['lastVote']['opinion'] == 'good') echo 'baad'; else echo 'good';?>">
                    <span><?php if ($data['lastVote']['opinion'] == 'good') echo substr($data['topic'][0]['title'],0,20) . ' Baad'; else echo substr($data['topic'][0]['title'],0,20) . ' Good';?></span>
                </a>
            </p>
        <?php else:?>
        
            <p class="clear">What do you think of <strong><?php echo $data['topic'][0]['title'];?></strong>:
        	
                <a href="/votes/cast/<?php echo $data['topic'][0]['guid'];?>/good" class="goodbtn" title="Vote: <?php echo $data['topic'][0]['title'];?> good"><span>Good</span></a>
                
                <a href="/votes/cast/<?php echo $data['topic'][0]['guid'];?>/baad" class="baadbtn" title="Vote: <?php echo $data['topic'][0]['title'];?> baad"><span>Baad</span></a>
            
            </p>
            
        <?php endif;?>
        
        <p class="clear intro comments"><a href="#disqus_thread">Comments...</a></p>

    </div>
    
    <p class="clear">
        <?php $n = count($data['tags']); $i = 1;?>
        
        <?php foreach ($data['tags'] as $tag):?>
        
            <?php if ($i < $n): ?><a href="/tag/<?php echo $tag['title'] ;?>" title="Find other topics tagged <?php echo $tag['title'] ;?>"><?php echo ucfirst($tag['title']) . '</a>, '; $i++; else: ?><a href="/tag/<?php echo $tag['title'] ;?>" title="Find other topics tagged <?php echo $tag['title'] ;?>"><?php echo ucfirst($tag['title']) . '</a>.'; endif;?>
            
        <?php endforeach;?>
    </p>
    
    <div class="left">
    
        <?php if (isset($data['overAllOpinion'])):?>
            
            <h3>Overall Opinion</h3>
                
            <p><?php echo $data['overAllOpinion']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['overAllOpinion']['percentages']['baad']?>% <strong>Baad</strong> from the last <?php echo ($data['overAllOpinion']['votes']['good']+$data['overAllOpinion']['votes']['baad']);?> opinions.</p>
            
            <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['overAllOpinion']['percentages']['baad'];?>,<?php echo $data['overAllOpinion']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Overall Mood Pie Chart" />
            
        <?php else:?>
            
            <h3>Be the First to Vote on This Topic</h3>
            
        <?php endif;?>
        
        <?php if (isset($_SESSION['userName'])):?>
            
            <?php if (isset($data['followedByStats'])):?>
                
                <h3>Opinion From Users You're Followed By</h3>
                    
                <p><?php echo $data['followedByStats']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['followedByStats']['percentages']['baad']?>% <strong>Baad</strong> from the last <?php echo ($data['followedByStats']['votes']['good']+$data['followedByStats']['votes']['baad']);?> opinions.</p>
                
                <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['followedByStats']['percentages']['baad'];?>,<?php echo $data['followedByStats']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Followed By Mood Pie Chart" />
                
            <?php endif;?>
            
            <?php if (isset($data['followedBy'])):?>
                
                <h3>Users you are followed by:</h3>
                
                <p>
                    <?php foreach($data['followedBy'] as $user):?>
                        <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                    <?php endforeach;?>
                </p>
                
            <?php endif;?>
            
        <?php endif;?>
        
    </div>
    
    <div class="right">
        
        <?php if (isset($_SESSION['userName'])):?>
            
            <?php if (isset($data['followingStats'])):?>
                
                <h3>Opinion From Users You're Following</h3>
                    
                    <p><?php echo $data['followingStats']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['followingStats']['percentages']['baad']?>% <strong>Baad</strong> from the last <?php echo ($data['followingStats']['votes']['good']+$data['followingStats']['votes']['baad']);?> opinions.</p>
                    
                    <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['followingStats']['percentages']['baad'];?>,<?php echo $data['followingStats']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Following Mood Pie Chart" />
                
            <?php endif;?>
            
            <?php if (isset($data['following'])):?>
                
                <h3>Users you are following:</h3>
                
                <p>
                    <?php foreach($data['following'] as $user):?>
                        <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                    <?php endforeach;?>
                </p>
                
            <?php endif;?>
            
            
            
        <?php else:?>
            
            <p class="intro">Your are not logged in, you can <a href="/login" title="Log In to Good Baad">Log In</a> or <a href="/signup" title="Sign up to Good Baad">Sign Up</a> up to post your own opinions and see what your friends have been up to.</p>

        <?php endif;?>
        
        <?php if (isset($data['feed'])):?>
        
            <h3>Recent votes on this topic:</h3>
                
            <ul>
                <?php foreach($data['feed'] as $opinion):?>
                
                <li><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst($opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
                
                <?php endforeach;?>
            </ul>
            
        <?php endif;?>
        
    </div>
    
<?php else:?>

    <h2><?php echo $data['msg'];?></h2>
    
<?php endif;?>

<div class="clear"></div>
<div id="disqus_thread" class="clear"></div><script type="text/javascript" src="http://disqus.com/forums/goodbaad/embed.js"></script><noscript><a href="http://goodbaad.disqus.com/?url=ref">View the discussion thread.</a></noscript><a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>


<?php #debug($data);?>