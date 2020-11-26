<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use samdark\sitemap\Sitemap;

class SiteMapGenerate extends Command
{

    const SITEMAP_PATH = __DIR__ . '/../../../public/sitemap_imoveis.xml';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um novo arquivo sitemap.xml dentro da pasta public/ puxando todas as URLs de imóveis e lançamentos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $sitemapService = new SitemapService( new Sitemap(self::SITEMAP_PATH));

        $apiBaseUrl = getenv('API_PATH');
        $siteBaseUrl = getenv('SITE_URL');
        $apiClientKey = getenv('API_KEY');

        $apiClientUrl = "{$apiBaseUrl}/{$apiClientKey}/";

        $client = new Client([
            'base_uri' => $apiClientUrl,
            //'timeout'  => 2.0,
        ]);

        $assetTypes = [
            'venda' => 'imovel',
            'aluguel' => 'imovel',
            'lancamentos' => 'lancamento'
        ];

        $items = [];

        foreach ($assetTypes as $key => $type) {

            $urlPart = $type;
            $type = $key;

            //do the first request
            $resopnse = $client->get("Anuncio?Finalidade={$type}&Page=1&PageSize=12");
            $content = json_decode($resopnse->getBody()->getContents());

            $total = $content->totalCount;
            $pagesize = $content->pagesize;

            $pages = $total / $pagesize;
            $pages = intval(ceil($pages));

            for ($i=1; $i <= $pages; $i++) {

                $resopnse = $client->get("Anuncio?Finalidade={$type}&Page={$i}&PageSize=12");
                $content = json_decode($resopnse->getBody()->getContents());

                foreach ($content->items as $item) {
                    $items[] = $item;
                }

                echo "Página {$i} de {$type}\n";
            }

            $collection = Collection::make($items);
            $sitemapService->build($collection, "{$siteBaseUrl}{$urlPart}/");
        }

        //Escreve do sitemap.xml
        $sitemapService->save();
    }
}
