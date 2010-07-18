<div id="here"></div>

<div class="left">

    <?php if (isset($_SESSION['userName'])):?>
    
        <h3>Your Overall Opinion</h3>
        <?php if (isset($data['userTotal']['percentages'])):?>
            
            <p><?php echo $data['userTotal']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['userTotal']['percentages']['baad']?>% <strong>Baad</strong>.</p>
                
            <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['userTotal']['percentages']['baad'];?>,<?php echo $data['userTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Your pie chart" />
            
        <?php else:?>
        
            <p>You haven't posted any opinions yet.</p>
            
        <?php endif;?>
        
    <?php endif;?>
    
    <?php if (isset($data['activeUsers'])):?>
        
        <h3>Top 5 Most Active Users</h3>
        <p>(From the Last <?php echo TOTAL;?> Votes)</p>
        
        <ul>
            <?php foreach ($data['activeUsers'] as $user):?>
                <li><a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>: <strong><?php echo $user['total'];?> votes</strong></li>
            <?php endforeach;?>
        </ul>
        
        
    <?php endif;?>
    
    <?php if (isset($data['grandTotal'])):?>
    
        <h3>The World is Currently <em><?php echo $data['grandTotal']['percentages']['good']?>% Good, <?php echo $data['grandTotal']['percentages']['baad']?>% Baad</em></h3>
        
        <p>According to the <a href="/worldometer" title="Updates on the state of the world">Good Baad Worldometer</a>.</p>
        
        <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['grandTotal']['percentages']['baad'];?>,<?php echo $data['grandTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="Overall Mood Pie Chart" />
        
        <p class="intro">Subscribe to the <a href="/worldometer" title="Updates on the state of the world">Worldometer</a></p>
        
        
    <?php endif;?>
    
    <h3>Current Top 5 Hottest Topics:</h3>
    
    <?php if (isset($data['mostVotes'])):?>
        
        <ol>
            <?php foreach($data['mostVotes'] as $topic):?>
                <li><a href="/topics/<?php echo $topic['handle'];?>" title="Opinions on the topic: <?php echo $topic['title'];?>"><?php echo $topic['title']?></a></li>
            <?php endforeach;?>
        </ol>
        
    <?php endif;?>
    
    <?php if (isset($_SESSION['userName'])):?><p class="intro"><a href="/users/history" title="Your vote history">View your voting history here...</a></p><?php endif;?>
    
</div>

<div class="right">
    
    <?php if (isset($_SESSION['userName'])):?>
        
        <?php if (isset($data['followedByGrandTotal'])):?>
            
            <h3>Overall Friends' Opinion</h3>
            
            <?php if (isset($data['followedByGrandTotal']['percentages'])):?>
            
                <p><?php echo $data['followedByGrandTotal']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['followedByGrandTotal']['percentages']['baad']?>% <strong>Baad</strong></p>
                
                <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['followedByGrandTotal']['percentages']['baad'];?>,<?php echo $data['followedByGrandTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="A pie chart showing the overall opinion of users you are following." />
                
            <?php else:?>
            
                <p>The users you are following have not voted on any topics yet.</p>
                
            <?php endif;?>
            
            <h3>Users you are following:</h3>
                
            <?php if (isset($data['followedBy'])):?>
                <p>
                    <?php foreach($data['followedBy'] as $user):?>
                        <a href="/users/<?php echo $user['username'];?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                    <?php endforeach;?>
                </p>
            <?php endif;?>
            
        <?php else:?>
        
            <p class="intro">You Are not following any user's opinions yet...</p>
            
        <?php endif;?>
        
        <?php if (isset($data['followedByFeed'])):?>
            
            <h3>Recent Opinions From Users You are Following</h3>
            
            <ul>
                <?php foreach($data['followedByFeed'] as $opinion):?>
                
                <li class="votable" id="<?php echo $opinion['guid'];?>"><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst($opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
                
                <?php endforeach;?>
            </ul>
                
        <?php endif;?>
        
    <?php else:?>
    
        <p class="intro">Good Baad is only interested in two opinions (whatever the topic): <strong>Good</strong> or <strong>Baad.</strong></p>
        
        <p><strong><a href="/votes" title="Vote Good or Baad on any topic">What do you think?</a></strong></p>
        
        <?php if (isset($data['masterFeed'])):?>
        
            <h3>Recent Votes From Good Baad Users</h3>
                
            <ul>
                <?php foreach($data['masterFeed'] as $opinion):?>
                
                <li class="votable" id="<?php echo $opinion['guid'];?>"><a href="/users/<?php echo $opinion['username'];?>" title="<?php echo ucfirst($opinion['username']);?>'s opinions page"><?php echo ucfirst( $opinion['username']);?></a> voted <a href="/topics/<?php echo $opinion['handle'];?>" title="Opinions on the topic: <?php echo $opinion['title'];?>"><?php echo $opinion['title'];?></a>: <strong><?php echo $opinion['opinion'];?></strong></li>
                
                <?php endforeach;?>
            </ul>
        
        <?php endif;?>
        
    <?php endif;?>
    
</div>

<?php if (!$m): ?>
    
    <div class="jCarouselLite" id="move">
        <?php require_once(SITE_PATH . 'webroot' . DIR . 'newslist.html'); ?>
    </div>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" ></script>
    <script type="text/javascript" src="http://www.goodbaad.com/webroot/js/jcarousellite.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function() {

            $("#move").remove().appendTo("#here"); 
            
            $(".jCarouselLite").jCarouselLite({
                btnNext: ".next",
                btnPrev: ".prev",
                hoverPause:true, 
                visible: 4,
                auto:1500,
                speed:500             
            });
            
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
    
<?php endif;?>

<div class="clear"></div>
<script type="text/javascript" src="http://disqus.com/forums/goodbaad/combination_widget.js?num_items=5&color=grey&default_tab=popular"></script>
