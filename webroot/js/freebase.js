var choice = function (data) {
    
    var detail = '';
    var test = "<ul class=\"result\">";

    for(i=0;i<data.result.length;i++)
    {
        if ((data.result[i].name != null)&&(data.result[i].type[0].name != 'Domain' && data.result[i].type[0].name != 'Content'))
        {
            test = test + "<li class=\"result\">";
            test = test + "<h4>" + data.result[i].name;
            if (data.result[i].alias != '') test = test + " (" + data.result[i].alias + ")";
            test = test + "</h4>";
            
            type = data.result[i].type;
            for(n=0;n<type.length;n++)
            {
                if (n == (type.length-1))
                {
                    detail = detail + type[n].name + ".";
                }
                else
                {
                    detail = detail + type[n].name + ", ";
                }
            }
            
            if (data.result[i].image != null)
            {
                test = test + "<div><img src='http://www.freebase.com/api/trans/image_thumb"+data.result[i].image.id+"' /></div>";
            }
            
            test = test + "<p>" + detail + "</p>";
            detail = '';
            
            test = test + "<p class=\"verdict\">Click to add your verdict:</p>";
            test = test + "<p class=\"clear\">";
            test = test + "<a href='/votes/cast/" + data.result[i].guid.replace(/[#]/g, "") + "/good' class=\"goodbtn\" title=\"Vote: "+data.result[i].name+" Good\"><span>Good</span></a>";
            test = test + "<a href='/votes/cast/" + data.result[i].guid.replace(/[#]/g, "") + "/baad' class=\"baadbtn\" title=\"Vote: "+data.result[i].name+" Baad\"><span>Baad</span></a>";
            test = test + "</p>";
            
            test = test + "</li>";
        }
    }
    
    test = test + "</ul>";
    
    if (i == 0) test = "<ul><li><p>Sorry, no freebase topics matched your search, please check your spelling and retry.</p><p>Or, maybe Freebase doesn't know about this topic yet, if so you shoud add it yourself at <a href='http://www.freebase.com/' target='blank'>http://www.freebase.com/</a> or <a href='http://www.wikipedia.org/' target='blank'>http://www.wikipedia.org/</a></p>";
    
    test = test + "<p><em>Source: <a href='http://www.freebase.com/' target='blank'>Freebase</a>, The World's Database</em></p></li></ul>";
    
    document.getElementById ('result').innerHTML = test;
};

var getjs = function (value) {
    if (! value)
        return;

    url = 'http://freebase.com/api/service/search?category=object&limit=15&prefix=' + value + '&callback=choice';

    document.getElementById ('result').innerHTML = 'Checking ...';
    var elem = document.createElement ('script');
    elem.setAttribute ('src', url);
    elem.setAttribute ('type','text/javascript');
    document.getElementsByTagName ('head') [0].appendChild (elem);
};

/*
function highlight(field)
{
    field.focus();
    field.select();
}

highlight(document.getElementById ('searchString'));*/