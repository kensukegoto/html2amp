<?php
if(isset($_GET['siteurl'])){
    
    require 'vendor/autoload.php';
    
    $url = $_GET['siteurl'];
    $client = new Goutte\Client();
    $crawler = $client -> request('GET',$url);
    
    $tit = $crawler->filter('title')->text();
    $disc = $crawler->filterXpath('//meta[@name="description"]')->attr('content');
    $keywords = $crawler->filterXpath('//meta[@name="keywords"]')->attr('content');
    $ogimg = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
    $ogimg_secure = $crawler->filterXpath('//meta[@property="og:image:secure_url"]')->attr('content');
    
    $contents = $crawler->filter('section.module--detail-content')->html();
    // 画像のパスを差し替える
    $imgs = $crawler->filter('.body_img img');
    
    /* 絶対パスをここに指定 */
    $absPath = '';
    

    $bf2af = [
        'bf'=>['writeInclude("./modules/totop.html");'],
        'af'=>['']
    ];

    $imgs->each(function($e){

        global $absPath;
        global $bf2af;
        $path0 = $e->attr('src');
        $path1 = $absPath.$path0;
        $bf2af['bf'][] = $path0;
        $bf2af['af'][] = $path1;

    });
    
    var_dump($bf2af);
    
    $contents = str_replace($bf2af['bf'],$bf2af['af'],$contents);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php echo $contents; ?>
</body>
</html>
<?php   
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>フォーム</title>
</head>
<body>
    <form action="scrape.php" method="get">
        <input type="text" name="siteurl">
        <input type="submit" value="送信">
    </form>
    <p><?php echo isset($_GET['siteurl'])?$_GET['siteurl']:""; ?></p>
</body>
</html>
<?php
      }