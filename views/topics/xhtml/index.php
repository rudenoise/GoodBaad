<div id="here"></div>

<div class="left">
    <?php if (isset($data['total'])):?>
        
        <h3>Current Top 5 Hottest Topics:</h3>

        <ol>
            <?php foreach($data['total'] as $topic):?>
            
                <li><a href="/topics/<?php echo $topic['handle'];?>" title="Opinions on the topic: <?php echo $topic['title'];?>"><?php echo $topic['title']?></a></li>
                
            <?php endforeach;?>
        </ol>
            
    <?php endif;?>
    
    <?php if (isset($data['goodest'])):?>
        
        <h3>Current Top 5 Topics Voted Good:</h3>

        <ol>
            <?php foreach($data['goodest'] as $goodest):?>
                <li><a href="/topics/<?php echo $goodest['handle'];?>" title="Opinions on the topic: <?php echo $goodest['title'];?>"><?php echo $goodest['title']?></a></li>
            <?php endforeach;?>
        </ol>
    
    <?php endif;?>
    
    <?php if (isset($data['baadest'])):?>
        
        <h3>Current Top 5 Topics Voted Baad:</h3>
    
        <ol>
            <?php foreach($data['baadest'] as $baadest):?>
                <li><a href="/topics/<?php echo $baadest['handle'];?>" title="Opinions on the topic: <?php echo $baadest['title'];?>"><?php echo $baadest['title']?></a></li>
            <?php endforeach;?>
        </ol>
        
    <?php endif;?>
    
</div>

<div class="right">
    
    <?php if (isset($_SESSION['userName'])):?>
        
        <?php if (isset($data['followingTotal'])):?>
            
            <h3>Current Top 5 Hottest Topics From Your Friends:</h3>
        
            <ol>
                <?php foreach($data['followingTotal'] as $friendTopic):?>
                    <li><a href="/topics/<?php echo $friendTopic['handle'];?>" title="Opinions on the topic: <?php echo $friendTopic['title'];?>"><?php echo $friendTopic['title']?></a></li>
                <?php endforeach;?>
            </ol>
            
        <?php endif;?>
        
        <?php if (isset($data['followingGoodest'])):?>
            
            <h3>Current Top 5 Topics Voted Good By Your Friends:</h3>
        
            <ol>
                <?php foreach($data['followingGoodest'] as $followingGoodest):?>
                    <li><a href="/topics/<?php echo $followingGoodest['handle'];?>" title="Opinions on the topic: <?php echo $followingGoodest['title'];?>"><?php echo $followingGoodest['title']?></a></li>
                <?php endforeach;?>
            </ol>
            
        <?php endif;?>
        
        <?php if (isset($data['followingBaadest'])):?>
            
            <h3>Current Top 5 Topics Voted Baad By Your Friends:</h3>
        
            <ol>
                <?php foreach($data['followingBaadest'] as $followingBaadest):?>
                    <li><a href="/topics/<?php echo $followingBaadest['handle'];?>" title="Opinions on the topic: <?php echo $followingBaadest['title'];?>"><?php echo $followingBaadest['title']?></a></li>
                <?php endforeach;?>
            </ol>
            
        <?php endif;?>
        
        <?php if (isset($data['following'])):?>
            
            <h3>Users You're following:</h3>
            
            <p>
                <?php foreach($data['following'] as $user):?>
                    <a href="/users/<?php echo ucfirst($user['username']);?>" title="<?php echo ucfirst($user['username']);?>'s opinions page"><?php echo ucfirst($user['username']);?></a>
                <?php endforeach;?>
            </p>
        
        <?php else:?>
        
            <p class="intro">You Are not following any user's opinions yet...</p>
            
        <?php endif;?>
        
    <?php else:?>
    
        <p class="intro">Your are not logged in, you can <a href="/login" title="Log In to Good Baad">Log In</a> or <a href="/signup" title="Sign up to Good Baad">Sign Up</a> up to post your own opinions and see what your friends have been up to.</p>
        
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