<?php

namespace App\Console\Commands;

use App\Services\MenuItemService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Str;

class FetchMenuItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:menu-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches menu items from the configured menu-items.url and imports them into the database.';

    protected HttpClient $httpClient;
    protected MenuItemService $menuItemService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        HttpClient $httpClient,
        MenuItemService $menuItemService
    ) {
        parent::__construct();

        $this->httpClient = $httpClient;
        $this->menuItemService = $menuItemService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = config('menu-items.url');
        $req = $this->httpClient->acceptJson()->get($url);
        if (! $req->successful()) {
            $this->error('Failed to fetch menu item data. Please see the following exception.');
            $req->throw();

            return 1;
        }

        $data = $req->json();
        if (! \is_array($data) || ! \array_key_exists('menu_items', $data)) {
            $this->error('Received malformed menu item data:');
            dump($data);

            return 1;
        }

        $importedItems = $this->menuItemService->import($data['menu_items']);
        $this->info(sprintf(
            'Imported %d new %s.',
            $importedItems,
            Str::plural('item', $importedItems),
        ));

        return 0;
    }
}
