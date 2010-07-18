<div id="here"></div>

<h2>Vote On Any Topic You Like:</h2>

<?php if (isset($data['msg'])): ?><h3><?php echo $data['msg']; ?></h3><?php endif;?>

<form action="/votes/search" method="post" name="Search" id="SearchTopicsForm">
    <ul id="find">
        
        <li><label>Enter anything</label>: <input id="searchString" name="data[Topic][Str]" onkeyup="getjs (this.value);" type="text"></li>
        
        <li><label><input type="submit" value="Go" name="Search"/></label></li>
        
    </ul>
    
</form>

<div id="result">
    
    <?php if (isset($data['msg'])): ?>
        
        <p><?php echo $data['msg'];?></p>
        
    <?php endif; ?>
    
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