<div id="here"></div>

<h2>Vote On Any Topic You Like:</h2>

<form action="/votes/search" method="post" name="Search" id="SearchTopicsForm">
    <ul id="find">
        
        <li><label>Enter a anything</label>: <input id="searchString" name="data[Topic][Str]" onkeyup="getjs (this.value);" type="text"></li>
        
        <li><label><input type="submit" value="Go" name="Search"/></label></li>
        
    </ul>
    
</form>


<?php if (isset($data['msg'])): ?><p class="clear"><?php echo $data['msg']; ?></p><?php endif;?>

<div id="result">
    <?php if (isset($data[0])):?>
        
        <ul class="result">
            <?php foreach ($data as $topic):?>
            
                <li class="result">
                    
                    <h4><?php echo $topic['name'];?></h4>
                    
                    <?php if (isset($topic['image'])):?>
                        <div><img src="http://www.freebase.com/api/trans/image_thumb<?php echo $topic['image'];?>"/></div>
                    <?php endif;?>
                    
                    <p>
                    <?php
                    $n = count($topic['types']);
                    $i = 1;
                    if (isset($topic['types'][0])):
                        foreach ($topic['types'] as $type):
                            if ($i < $n):
                                echo $type .', ';
                            else:
                                echo $type . '.';
                            endif;
                            $i++;
                        endforeach;
                    endif;
                    ?>
                    </p>
                    
                    <p class="verdict">Click to add your verdict:</p>
                    
                    <p class="clear">
                        
                        <a href="/votes/cast/<?php echo $topic['guid'];?>/good" class="goodbtn" title="Vote: <?php echo $topic['name'];?> good"><span>Good</span></a>
                        
                        <a href="/votes/cast/<?php echo $topic['guid'];?>/baad" class="baadbtn" title="Vote: <?php echo $topic['name'];?> baad"><span>Baad</span></a>
                        
                    </p>
                    
                </li>
                
            <?php endforeach;?>
            
        </ul>
        
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

<script type="text/javascript" src="/webroot/js/freebase.js"></script>