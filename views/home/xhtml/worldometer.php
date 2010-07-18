<h2>The Good Baad Worldometer</h2>

<div class="left">
    
    <?php if (isset($data['grandTotal'])):?>
    
        <h3>The World is Currently <em><?php echo $data['grandTotal']['percentages']['good']?>% Good, <?php echo $data['grandTotal']['percentages']['baad']?>% Baad</em></h3>
           
        <p><?php echo $data['grandTotal']['percentages']['good']?>% <strong>Good</strong>, <?php echo $data['grandTotal']['percentages']['baad']?>% <strong>Baad</strong>.</p>
        
        <img src="http://chart.apis.google.com/chart?cht=p&chf=bg,s,ffffff&chd=t:<?php echo $data['grandTotal']['percentages']['baad'];?>,<?php echo $data['grandTotal']['percentages']['good'];?>&chs=220x110&chl=Baad|Good&chco=000000" alt="The Current State of the World in a Pie Chart" />
        
    <?php endif;?>
    
    <p class="intro">Follow the <a href="http://twitter.com/worldometer" title="Follow the Worldometer on Twitter" target="blank">Worldometer on Twitter</a> for updates throughout the day.</p>
    
</div>

<div class="right">
    
    <form action="http://feedburner.google.com/fb/a/mailverify" method="post" class="vform" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=GoodBaadWorldometer', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
        
        <fieldset>
            
            <legend>Recieve daily updates:<legend>
            
            <p><label>Enter your address:</label><input type="text" style="width:140px" name="email"/></p>
            
            <input type="hidden" value="GoodBaadWorldometer" name="uri"/>
            
            <input type="hidden" name="loc" value="en_US"/>
            
            <p><input type="submit" value="Subscribe" /></p>
            
            <p>Delivered by <a href="http://feedburner.google.com" target="_blank">FeedBurner</a></p>
        
        </fieldset>
        
    </form>
    
    <p class="intro">Keep up-to-date with the worldometer on your feed reader via <a href="http://feeds2.feedburner.com/GoodBaadWorldometer?alt=rss" title="Subscribe to the Worldometer RSS Feed">RSS</a> and <a href="http://feeds2.feedburner.com/GoodBaadWorldometer" title="Subscribe to the Worldometer Atom Feed">Atom</a>.</p>
    
    <p><a href="http://feeds2.feedburner.com/GoodBaadWorldometer" title="Subscribe to the Worldometer Feed"><img src="/webroot/img/rss.png" alt="RSS Feed" /></a> <a href="http://twitter.com/worldometer" title="Follow the Worldometer on Twitter" target="blank"><img src="http://assets1.twitter.com/images/twitter_logo_s.png" /></a></p>
    
    
</div>

<?php include_once(SITE_PATH . 'webroot' . DIR . 'worldometer.html');?>

<div id="disqus_thread"></div><script type="text/javascript" src="http://disqus.com/forums/goodbaad/embed.js"></script><noscript><a href="http://goodbaad.disqus.com/?url=ref">View the discussion thread.</a></noscript><a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>