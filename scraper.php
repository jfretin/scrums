<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page
$html = scraperwiki::scrape("http://www.leboncoin.fr/telephonie/675699535.htm?ca=17_s");
//
// // Find something on the page using css selectors
$dom = new simple_html_dom();
$dom->load($html);

$phoneLink = $dom->find("#phoneNumber a", 0);
if ($phoneLink->href != "") {
    $jsonImg = scraperwiki::scrape("http://www2.leboncoin.fr/ajapi/get/phone?list_id=675699535");
    if ($jsonImg != '""') {
        $i = json_decode($jsonImg);
        $src = $i->phoneUrl;
        if ($src != "") {
            $md5 = md5($src);
            if (scraperwiki::select("* from data where 'adId'='675699535'")) {
                echo "675699535 already in DB!\n";
            } else {
                $img = base64_encode(file_get_contents($src));
                scraperwiki::save_sqlite(array('adId'), array('md5' => $md5, 'adId' => "675699535", 'content' => $img));
                echo "saved 675699535 in DB\n";
            }
        }
    }
}

//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")

// You don't have to do things with the ScraperWiki library. You can use whatever is installed
// on Morph for PHP (See https://github.com/openaustralia/morph-docker-php) and all that matters
// is that your final data is written to an Sqlite database called data.sqlite in the current working directory which
// has at least a table called data.
?>
