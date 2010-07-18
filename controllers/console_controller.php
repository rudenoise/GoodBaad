<?php
Class ConsoleController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
        
        $this->accountModel = new AccountModel;
    }
    
    function index()
    {
        echo "HELLO!\nI'm a console script";
    }
    
    /*
     * START NEWS TICKER TASKS
     */
    
    function newsTopics()
    {
        $accesskey      = '63baa1ae69d7f02cbe898165ef8c36cf';
        $sharedsecret   = '31f207ed005284ae84b738584b38d3de';
    
        /*
         * COREINPUT IDS MUST BE IN ALPHABETICAL ORDER
         */
        #$coreinput[0]   = "0eAceMCfZg0Zz"; #Reuters (world)
    
        $coreinput[0]   = '01Sl7SVbPncrJ'; #ZDNet UK
        $coreinput[1]   = '04Xt71JaPXcaH'; #BBC News
        $coreinput[2]   = '05A46FB2fM8MU'; #The Independent
        $coreinput[3]   = '08hUfHm7yF1n7'; #Guardian Unlimited
        $coreinput[4]   = '0a0F0VifAodow'; #The New York Times
        #$coreinput[4]   = '0bTydbo3fg1sK'; #ZDNet
    
    
        $url = 'http://freeapi.daylife.com/jsonrest/publicapi/4.6/source_getTopics?';
    
        $url .= 'source_id=' . $coreinput[0] . '&source_id=' . $coreinput[1] . '&source_id=' . $coreinput[2] . '&source_id=' . $coreinput[3] . '&source_id=' . $coreinput[4];
    
        $signature = hash('md5', $accesskey.$sharedsecret.$coreinput[0].$coreinput[1].$coreinput[2].$coreinput[3].$coreinput[4]);
    
        $url .= '&'.date('Y-m-d',time() - (1*60*60)).'&end_time='.date('Y-m-d',time()).'&limit=60';
    
        $url .= '&accesskey='.$accesskey.'&signature=' . $signature;
    
        $results = $this->jsonConnect($url);
    
        if (isset($results->response->payload->topic))
        {
            $this->consoleModel = new ConsoleModel;
            
            foreach ($results->response->payload->topic as $result)
            {
                $str = 'http://www.freebase.com/api/service/mqlread?query='.urlencode('{"query":[{"guid":null,"id":null,"limit":1,"name":"'.$result->name.'","type":"/common/topic"}]}');
                
                if ($topic = $this->jsonConnect($str))
                {
                    if (isset($topic->result[0]))
                    {
                        $topic2['guid'] = preg_replace('[#]','',$topic->result[0]->guid);
                        
                        $topic2['name'] = $topic->result[0]->name;
                        
                        if ($dbTopic = $this->consoleModel->getTopics($topic2['guid']))
                        {
                            $topic2['handle'] = $dbTopic[0]['handle'];
                        }
                    }
                }
                
                if (isset($topic2)) $results2[] = $topic2;
                
                unset($topic2);
            }
        }
    
        if (isset($results2))
        {
            shuffle($results2);
            
            if ($this->writeNewsFile($results2))
            {
                echo 'SUCCESS';
            }
           
           print_r($results2);
        }
    }
    
    private function writeNewsFile($results)
    {
        if ($results)
        {
            $content = '<ul>';
        
            foreach($results as $item)
            {
                
                    $content .= '
    <li>
        <h5>';
        
        if (strlen($item['name']) > 15)
        {
            $item['nameLong'] = $item['name'];
            
            $item['name'] = substr($item['name'], 0, 15) . '...';
        }
        
        if (isset($item['handle']))
        {
            $content .='<a href="http://www.goodbaad.com/topics/' . $item['handle'] . '" title="Opinions on the topic: ' . $item['nameLong'] . '">' . $item['name'] . '</a>';
        }
        else
        {
            $content.= $item['name'];
        }
        
        $content .= '</h5>';
        
        $content .='
        <p>vote: <a href="http://www.goodbaad.com/votes/cast/'.$item['guid'].'/good" title="Vote ' . $item['nameLong'] . ': Good">Good</a> / <a href="http://www.goodbaad.com/votes/cast/'.$item['guid'].'/baad" title="Vote ' . $item['nameLong'] . ': Baad">Baad</a></p>
    </li>
';
                
            }
    
        $content .= '</ul>
<p><a href="#" class="prev" title="Rewind">&lt;&lt;</a> | <a href="#" class="next" title="Fast Forward">&gt;&gt;</a></p>';
        
        
            $fp = fopen(SITE_PATH . 'webroot' . DIR . 'newslist.html', 'w');
                
            fwrite($fp, $content);
            
            fclose($fp);
            
            return true;
        }
    }
    /*
     * END NEWS TICKER TASKS
     */
    
    /*
     * START WORLDOMETER TASKS
     */
    
    protected function worldometer($interval)
    {
        
        
        $this->consoleModel = new ConsoleModel;
        
        if ($interval == 'days') $i = 8;
        
        if ($interval == 'hours') $i = 2;
        
        
        
        while ($i > 0)
        {
            if ($interval == 'days') $time = date('Y-m-d',time() - ($i*24*60*60)) .' 23:59:59';
            
            if ($interval == 'hours')
            {
                if ($i == 2)
                {
                    $time = date('Y-m-d H:i:s',time() - (1*30*60));
                }
                else
                {
                    $time = date('Y-m-d H:i:s',time());
                }
            }
            
            $rslt = $this->consoleModel->getDayStats($time);
            
            $results[$time]['percent'] = $this->percentages($rslt[0]['good'],$rslt[0]['baad']);
            
            if (isset($last))
            {
                $change['good'] = $results[$time]['percent']['good'] - $last['good'];
                
                $change['baad'] = $results[$time]['percent']['baad'] - $last['baad'];
                
                $results[$time]['change'] = $change;
            }
            
            $last = $results[$time]['percent'];
            
            $i = $i - 1;
        }
        
        $results;
        
        array_shift($results);
        
        return array_reverse($results);
    }
    
    function tweetWorldometer()
    {
        $rslt = $this->worldometer('hours');
        
        $key = key($rslt);
        
        if ($rslt[$key]['change']['good'] == 0)
        {
            $msg = 'The world is '.$rslt[$key]['percent']['good'].'% GOOD, '.$rslt[$key]['percent']['baad'].'% BAAD. The world has not changed in the last half hour. #goodbaad http://bit.ly/4rAGHG';
        }
        elseif ($rslt[$key]['change']['good'] > 0)
        {
            $msg = 'The world is '.$rslt[$key]['percent']['good'].'% GOOD. Change: '. $rslt[$key]['change']['good'] .'% more GOOD in the last half hour. #goodbaad http://bit.ly/4rAGHG';
        }
        elseif ($rslt[$key]['change']['good'] < 0)
        {
            $msg = 'The world is '.$rslt[$key]['percent']['baad'].'% BAAD. Change: '. $rslt[$key]['change']['baad'] .'% more BAAD in the last half hour. #goodbaad http://bit.ly/4rAGHG';
        }
        
        if ($this->postToTwitter('worldometer','W0rlD!',$msg))
        {
            echo strlen($msg) . ' - ' . $msg . "\n";
        }
    }
    
    private function postToTwitter($username,$password,$message)
    {
        $tweetUrl = 'http://www.twitter.com/statuses/update.xml';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$tweetUrl");
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "status=$message&source=goodbaadworldometer");
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        
        $result = curl_exec($curl);
        $resultArray = curl_getinfo($curl);
        
        if ($resultArray['http_code'] == 200)
        {
            echo "Tweet Posted\n$message";
        }
        else
        {
            echo 'Could not post Tweet to Twitter right now. Try again later.';
        }
        
        curl_close($curl);
    }
    
    function blogWorldometer()
    {
        $this->statModel = new StatModel;
        
        foreach ($this->worldometer('days') as $date => $detail)
        {
            $worldArr[$date] = $detail;
            
            $worldArr[$date]['total'] = $this->statModel->combo(array('date'=>$date),'total',5);
            
            $worldArr[$date]['good'] = $this->statModel->combo(array('date'=>$date),'goodest',5);
            
            $worldArr[$date]['baad'] = $this->statModel->combo(array('date'=>$date),'baadest',5);
        }
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>

<!-- RSS generated by GoodBaad Worldometer -->

<rss version="2.0" xmlns:blogChannel="http://www.goodbaad.com/worldometer/rss">
	
    <channel>
		<title>Good Baad Worldometer</title>
		
        <link>http://www.goodbaad.com/worldometer</link>
		
        <description>Keeping you up to date with the state of the world</description>
		
        <language>en-us</language>

		<copyright>Copyright 2009 Joel Hughes</copyright>
        
		<lastBuildDate>' . date('D, d M Y H:i:s',strtotime(key($worldArr))) . ' GMT</lastBuildDate>
        
		<docs>http://backend.userland.com/rss</docs>

		<generator>Good Baad Worldometer</generator>
        
		
        
		<managingEditor>joel@goodbaad.com</managingEditor>
        
		<webMaster>joel@goodbaad.com</webMaster>
        
		<ttl>360</ttl>
        ';
        
        foreach ($worldArr as $date => $detail)
        {
            
            $xml .= '
		<item>

			<title>The state of the world on '.date('D, d S M Y',strtotime($date)).'</title>
            
            <description>';
            
            $subxml .= '<h3>The state of the world is <em>'.$detail['percent']['good'].'% Good, '.$detail['percent']['baad'].'% Baad</em></h3>';
            
            if ($detail['change']['good'] > 0)
            {
                $subxml .= '<h4>The world has become '.$detail['change']['good'].'% more Good in the past 24 hours.</h4>';
            }
            elseif ($detail['change']['good'] < 0)
            {
                $subxml .= '<h4>The world has become '.$detail['change']['baad'].'% more Baad in the past 24 hours.</h4>';
            }
            else
            {
                $subxml .= '<h4>The world has not changed and is still '.$detail['percent']['good'].'% Good ('.$detail['percent']['baad'].'% Baad)</h4>
                <p>Despite the daily voting activity: the overall outlook remains the same.</p>';
            }
            
            $subxml .= '<p>The topics of the day:</p>';
            
            $subxml .= '
            <div class="smallFive">
                <h4>Popular Topics</h4>
                <ol>';
                    
                    foreach ($detail['total'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                    
                $subxml .= '
                </ol>
            </div>
            <div class="smallFive">';
                
                $subxml .= '
                <h4>Most Good</h4>
                <ol>';
                    
                    foreach ($detail['good'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                    
                $subxml .= '
                </ol>
            </div>
            <div class="smallFive">';
                
                $subxml .= '
                <h4>Most Baad</h4>
                <ol>';
                    
                    foreach ($detail['baad'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                
                $subxml .= '
                </ol>
            </div>';
            
            $subxml .= '
            <p>Have your say and get voting <strong><a href="http://www.goodbaad.com">www.goodbaad.com</a></strong>.</p>';
            
            $xml .= htmlspecialchars($subxml);
            
            unset($subxml);
            
            $xml .= '</description>

			<pubDate>'.date('D, d M Y H:i:s',strtotime($date)).' GMT</pubDate>
			
            <guid>http://www.goodbaad.com/worldometer#guid'.date('Y-m-d',strtotime($date)).'</guid>
        </item>
        
		';
            }
            
            $xml .= '
        
    </channel>
    
</rss>

        ';
        $fp = fopen(SITE_PATH . 'webroot' . DIR . 'rss.xml', 'w');
                
        fwrite($fp, $xml);
        
        fclose($fp);
    }
    
    function htmlWorldometer()
    {
        $this->statModel = new StatModel;
        
        foreach ($this->worldometer('days') as $date => $detail)
        {
            $worldArr[$date] = $detail;
            
            $worldArr[$date]['total'] = $this->statModel->combo(array('date'=>$date),'total',5);
            
            $worldArr[$date]['good'] = $this->statModel->combo(array('date'=>$date),'goodest',5);
            
            $worldArr[$date]['baad'] = $this->statModel->combo(array('date'=>$date),'baadest',5);
        }
        
        foreach ($worldArr as $date => $detail)
        {
            
        $xml .= '
		<div class="day">

			<h3 id=\'guid'.date('Y-m-d',strtotime($date)).'\'>The State of the World on '.date('D, d S M Y',strtotime($date)).'</h3>';
            
            $subxml .= '<h4><em>'.$detail['percent']['good'].'% Good, '.$detail['percent']['baad'].'% Baad</em>. ';
            
            if ($detail['change']['good'] > 0)
            {
                $subxml .= 'The World Has Become '.$detail['change']['good'].'% More Good in the Past 24 Hours.</h4>';
            }
            elseif ($detail['change']['good'] < 0)
            {
                $subxml .= 'The World Has Become '.$detail['change']['baad'].'% more Baad in the Past 24 Hours.</h4>';
            }
            else
            {
                $subxml .= '</h4>
                <p>Despite the daily voting activity: the overall outlook remains the same.</p>';
            }
            
            $subxml .= '<p>The topics of the day:</p>';
            
            $subxml .= '
            <div class="smallFive">
                <h4>Popular Topics</h4>
                <ol>';
                    
                    foreach ($detail['total'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                    
                $subxml .= '
                </ol>
            </div>
            <div class="smallFive">';
                
                $subxml .= '
                <h4>Most Good</h4>
                <ol>';
                    
                    foreach ($detail['good'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                    
                $subxml .= '
                </ol>
            </div>
            <div class="smallFive">';
                
                $subxml .= '
                <h4>Most Baad</h4>
                <ol>';
                    
                    foreach ($detail['baad'] as $topic)
                    {
                        $subxml .= '<li><a href=\'http://www.goodbaad.com/topics/'.$topic['handle'].'\'>'.$topic['title'].'</a></li>';
                    }
                
                $subxml .= '
                </ol>
            </div>';
            
            $xml .= $subxml;
            
            unset($subxml);
            
            $xml .= '
            <div class="clear"></div>
        </div>
        
		';
            }
            
            
        $fp = fopen(SITE_PATH . 'webroot' . DIR . 'worldometer.html', 'w');
                
        fwrite($fp, $xml);
        
        fclose($fp);
    }
    
    function emailList()
    {
        $this->UserModel = new UserModel;
        
        foreach ($this->UserModel->getUserEmails() as $user)
        {
            $emails[] = $user['email'];
        }
        
        foreach ($this->UserModel->getInviteList() as $user)
        {
            $emails[] = $user['email'];
        }
        
        $i=1;
        
        
        foreach (array_unique($emails) as $email)
        {
           if ($this->announcement($email))
           {
               echo "$i : Sent to $email\n";
               $i++;
           }
        }
      
        #echo 'DISABLED\n';
    }
    
    private function announcement($email)
    {
        $message = "*Good Baad : News*
        
Subscribers to the Worldometer will know that yesterday was the most good since records began (64.6% Good). This may have something to do with Good Baad being featured on Killer Startups yesterday.

It's gained us a few users and if you all vote it up (top left of the article) more could follow. http://www.killerstartups.com/User-Gen-Content/goodbaad-com-public-opinion-on-the-web

That's all for now...

The Good Baad Team

http://www.goodbaad.com

";
        
        if ($this->email($email,'Good Baad Announcement',$message))
        {
            return true;
        }
       
        #echo 'DISSABLED';
    }
    
    function siteMap()
    {
        $this->ConsoleModel = new ConsoleModel;
        
        $users = $this->ConsoleModel->getUsers();
        
        $topics = $this->ConsoleModel->getTopicsList();
        
        $tags = $this->ConsoleModel->getTags();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>http://www.goodbaad.com/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
   </url>

   <url>
      <loc>http://www.goodbaad.com/about/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>monthly</changefreq>
      <priority>.5</priority>
   </url>
   
   <url>
      <loc>http://www.goodbaad.com/contact/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>monthly</changefreq>
      <priority>.5</priority>
   </url>
   
   <url>
      <loc>http://www.goodbaad.com/worldometer/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
   </url>
   
    <url>
      <loc>http://www.goodbaad.com/votes/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>yearly</changefreq>
      <priority>.1</priority>
    </url>
    
    <url>
      <loc>http://www.goodbaad.com/find/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>yearly</changefreq>
      <priority>.1</priority>
    </url>
    
    <url>
      <loc>http://www.goodbaad.com/topics/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
    </url>';
        
        foreach ($topics as $topic)
        {
            $xml .='
    <url>
      <loc>http://www.goodbaad.com/topics/' . $topic['handle'] . '/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>.8</priority>
    </url>
            ';
        }
        
        $xml .= '
    <url>
      <loc>http://www.goodbaad.com/users/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
    </url>

';
        
        foreach ($users as $user)
        {
            $xml .= '
    <url>
      <loc>http://www.goodbaad.com/users/' . $user['username'] . '/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>.8</priority>
    </url>
';
        }
        
        foreach(range('a','z') as $letter)
        {
            $xml .= '
    <url>
      <loc>http://www.goodbaad.com/tags/' . $letter . '/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>.3</priority>
    </url>
';
        }
        
        foreach ($tags as $tag)
        {
            $xml .= '
    <url>
      <loc>http://www.goodbaad.com/tag/' . $tag['title'] . '/</loc>
      <lastmod>' . date('Y-m-d',time()) . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>.5</priority>
    </url>
';
        }
        
        $xml .= '</urlset>';
        
        $fp = fopen(SITE_PATH . 'webroot' . DIR . 'sitemap.xml', 'w');
                
        fwrite($fp, $xml);
        
        fclose($fp);
    }
    
    function test()
    {
        echo preg_replace(array('[ ]','[\.]'),array('-','-'),'rudenoise hello');
        
        $string = 'rudenoise';
        $patterns[0] = '[ ]';
        $replacements[0] = '-';
        
        
        echo "\n\n" . preg_replace($patterns, $replacements, $string);
    }
    
    function twitterComments($items = 'Luiz Felipe Scolari', $gbURL = 'http://www.goodbaad.com/topics/chelsea-fc')
    {
        if ($items != null)
        {
            $itemsArr = explode(' ', $items);
            
            $n = count($itemsArr);
            $i = 1;
            
            if (count($itemsArr) > 1)
            {
                $plus = '';
                
                
                foreach ($itemsArr as $item)
                {
                    if ($i != $n)
                    {
                        $plus .= $item . '+OR+';
                    }
                    else
                    {
                        $plus .= $item;
                    }
                    $i++;
                }
            }
            else
            {
                $plus = $items;
            }
            
            $str = '+%23goodbaad+OR+%23gdbd+' . $plus;
        }
        else
        {
            $str = '+%23goodbaad+OR+%23gdbd';
        }
        
        echo $url = 'http://search.twitter.com/search.json?q=' . $str;
        
        if ($json = $this->jsonConnect($url))
        {
            print_r($json);
        }
        
        if ($json = $this->jsonConnect('http://is.gd/api.php?longurl=' . $gbURL, false))
        {
            print_r($json);
        }
    }
}
?>