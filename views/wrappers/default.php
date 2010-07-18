<?php if (mobileBrowser()) $m = true; else $m = false;?>

<?php if ($m): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">
<?php else: ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php endif; ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
        
        <meta name="verify-v1" content="kywDLTza8W7fIYQhkO660l6tMXZ/Or92iBynafxdhx4=" />
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <meta name = "viewport" content = "width=device-width" />
        
        <link rel="apple-touch-icon" href="/webroot/img/sheep.png"/>
        
        <link rel="icon" href="/webroot/img/sheep.png" type="image/x-icon" />
        
        <link rel="shortcut icon" href="/webroot/img/sheep.png" type="image/x-icon" />
        
        <link rel="alternate" type="application/atom+xml" title="Good Baad Worldometer - Atom" href="http://feeds2.feedburner.com/GoodBaadWorldometer" />
        <link rel="alternate" type="application/rss+xml" title="Good Baad Worldometer - RSS" href="http://feeds2.feedburner.com/GoodBaadWorldometer?alt=rss" />
        <link rel="service.post" type="application/atom+xml" title="Good Baad Worldometer - Atom" href="http://feeds2.feedburner.com/GoodBaadWorldometer?alt=atom" />
        
        <link rel="alternate" type="application/atom+xml" title="The Good Baad Blog - Atom" href="http://blog.goodbaad.com/feeds/posts/default" />
        <link rel="alternate" type="application/rss+xml" title="The Good Baad Blog - RSS" href="http://blog.goodbaad.com/feeds/posts/default?alt=rss" />
        <link rel="service.post" type="application/atom+xml" title="The Good Baad Blog - Atom" href="http://www.blogger.com/feeds/4849555885524711213/posts/default" />

        
        <?php if (!$m):?>
            <link rel="stylesheet" type="text/css" href="/webroot/css/main.css" media="screen" />
            <!--[if gt IE 6]><link rel="stylesheet" href="/webroot/css/ie.css" type="text/css" media="screen" charset="utf-8" /><![endif]-->
        <?php endif;?>
        
        <title><?php echo $title; ?></title>
        
        <meta name="Description" content="Good Baad aims to be the definitive record of public opinion, and is only interested in two opinions: Good or Baad." />
        
    </head>
    
    <body>
        
        <div id="page">
            
            <div id="header">
                
                <h1><a href="/" title="Good Baad Home Page" rel="home">Good Baad</a></h1>
                
                
                <?php if (!$m):?><a href="#body" class="hide">Skip navigation and go to content</a><?php else:?><a href="#nav" name="top" class="hide">Skip to navigation</a><?php endif;?>
                
                <?php if (!$m): ?>
        	        
                    <div id="side">
                        
                        <?php if (!isset($active)): ?>
            	            
                            <p><a href="/login" title="Log In to Good Baad">Log In</a> | <a href="/signup" title="Sign up to Good Baad">Sign Up</a></p>
                            
            	        <?php else: ?>
                            
                            <p><a href="/logout" title="Log Out">Log Out</a> | <a href="/account" title="View your account details">Account</a></p>
                            
                        <?php endif; ?>
                        
                    </div>
                    
                    
                    <ul class="tabs">
                        
                        <li <?php if ($_GET['route'] == ''): ?>class="active"<?php endif;?>><a href="/" title="Good Baad Home Page" rel="home">Home</a></li>
                        
                        <li <?php if(preg_match('[vote]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/votes" title="Vote on any Topic">Vote</a></li>
                        
                        <li <?php if(preg_match('[topics]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/topics" title="Find the Hottest Topics on Good Baad">Topics</a></li>
                        
                        <li <?php if(preg_match('[users]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/users" title="See how Good Baad Users are Voting" >Users</a></li>
                        
                        <li <?php if(preg_match('[find]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/find" title="Find Stats From Users and Topics" >Find</a></li>
                        
                    </ul>
        	        
                <?php endif;?>
                
            </div>
            
            <div id="body" class="wrapper">
                
                <?php include($path); # MAIN CONTENT INSERTED HERE?>
                
            </div>
            
            <div>
                <p class="quiet statmsg"><small>The stats you see here are based on the last <?php echo TOTAL;?> votes.</small></p>
            </div>
            
            <div id="footer">
                
                <?php if ($m): ?>
        	        
                    <p><a href="#top" name="nav">Back to page top</a></p>
                    
                    <div id="side">
                        
                        <?php if (!isset($active)): ?>
            	            
                            <p><a href="/login" title="Log In to Good Baad">Log In</a> | <a href="/signup" title="Sign up to Good Baad">Sign Up</a></p>
                            
            	        <?php else: ?>
                            
                            <p><a href="/logout" title="Log Out">Log Out</a> | <a href="/account" title="View your account details">Account</a></p>
                            
                        <?php endif; ?>
                        
                    </div>
                    
                    <ul class="tabs">
                        
                        <li <?php if ($_GET['route'] == ''): ?>class="active"<?php endif;?>><a href="/" title="Good Baad Home Page" rel="home">Home</a></li>
                        
                        <li <?php if(preg_match('[vote]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/votes" title="Vote on any Topic">Vote</a></li>
                        
                        <li <?php if(preg_match('[worldometer]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/worldometer" title="Keeping You up to Date With the State of the World" >Worldometer</a></li>
                        
                        <li <?php if(preg_match('[topics]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/topics" title="Find the Hottest Topics on Good Baad">Topics</a></li>
                        
                        <li <?php if(preg_match('[users]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/users" title="See how Good Baad Users are Voting" >Users</a></li>
                        
                        <li <?php if(preg_match('[find]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/find" title="Find Stats From Users and Topics" >Find</a></li>
                        
                    </ul>
    	        
                <?php endif;?>
                    
                <ul class="tabs">
                    
                    <li <?php if(preg_match('[about]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/about">About</a></li>
                    
                    <li><a href="http://blog.goodbaad.com" title="Good Baad's Blog &amp; Podcast" target="_blank">Blog &amp; Podcast</a></li>
                    
                    <li <?php if(preg_match('[contact]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/contact">Contact Us</a></li>
                    
                    <li><a href="http://goodbaad.uservoice.com/" title="Report bugs or any feedback" target="_blank">Feedback</a></li>
                    
                    <?php if (!$m): ?>
                    
                        <li <?php if(preg_match('[worldometer]',$_GET['route'])): ?>class="active"<?php endif;?>><a href="/worldometer" title="Keeping You up to Date With the State of the World" >Worldometer</a></li>
                        
                    <?php endif;?>
                    
                </ul>
                
                <p class="quiet"><small><a href="http://www.joelhughes.co.uk/" title="Joel Hughes" target="_blank">Joel Hughes</a> <?php echo date('Y');?></small></p>
                    
            </div>
            
        </div>
         
        <?php if (!$m):?><script src="http://goodbaad.uservoice.com/pages/general/widgets/tab.js?alignment=left&amp;color=CCCCCC" type="text/javascript"></script><?php endif;?>
        
        <?php if(preg_match('[Topics]',$title)): ?>
            <script type="text/javascript">
            //<![CDATA[
            (function() {
            		var links = document.getElementsByTagName('a');
            		var query = '?';
            		for(var i = 0; i < links.length; i++) {
            			if(links[i].href.indexOf('#disqus_thread') >= 0) {
            				query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
            			}
            		}
            		document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/goodbaad/get_num_replies.js' + query + '"></' + 'script>');
            	})();
            //]]>
            </script>

        <?php endif;?>
        
        <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
        try {
        var pageTracker = _gat._getTracker("UA-6723848-1");
        pageTracker._trackPageview();
        } catch(err) {}</script>
        
    </body>
    
</html>
