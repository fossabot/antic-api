<?php

namespace App\Console\Commands;

use App\Models\Site;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class BaiduSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:seo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $guzzleClient;
    public $crawler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->guzzleClient = new GuzzleClient([
            'timeout' => 10,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Host' => 'www.baidu.com',
                'Pragma' => 'no-cache',
                'Referer' => 'https://www.baidu.com',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-User' => '?1',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36',
                'Cookie' => env('COOKIE'),
            ],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // https://www.google.com/search?q=site:sodu.ee
        // 获取所有站点
        $sites = Site::all();
        // 轮流查询
        foreach ($sites as $site) {
            $count = $this->spider($site->domain);
            $site->seo = $count;
            $site->save();
        }
    }

    public function spider($domain)
    {
        $count = null;

        $url = 'http://www.baidu.com/s?wd=site:'.$domain;
        echo $url.PHP_EOL;

        try {
            $response = $this->guzzleClient->request('GET', $url);
        } catch (\Exception  $e) {
            echo $e->getMessage();
        }

        $content = $response->getBody()->getContents();

        if (preg_match('/找到相关结果数约(?P<count>[\d,]+)个/', $content, $match)) {
            $count = str_replace(',', '', $match['count']);
        } else {
            $crawler = new Crawler($content);
            try {
                $count = $crawler->filterXPath('//span/b')->text();
                $count = str_replace(',', '', $count);
//                if (preg_match('/该网站共有 (?P<count>[\d,]+) 个网页被百度收录/', $content, $match)) {
//                    $count = str_replace(',', '', $match['count']);
//                }
            } catch (\Exception $e) {
                echo  $e->getMessage();
            }
        }

        if ($count === null) {
            if (preg_match('/很抱歉，没有找到与/', $content, $matchs)) {
                $count = 0;
            }
        }

        echo $count.PHP_EOL;

        return $count;
    }
}
