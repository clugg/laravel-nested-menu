<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

class MenuItemService
{
    protected ?Collection $_cache = null;

    /**
     * Gets the current menu items, including caching to
     * prevent unnecessary queries during recursive imports.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function _getCurrentData(): Collection
    {
        if ($this->_cache !== null) {
            return $this->_cache;
        }

        return $this->_cache = MenuItem::all();
    }

    /**
     * Recursively imports new menu item data into the database.
     *
     * @param array $data   data to import
     * @param ?int $parent  ID of parent item if applicable, null otherwise
     *
     * @return int          the number of new items imported
     */
    public function import(array $data, ?int $parent = null): int
    {
        $currentData = $this->_getCurrentData();
        $importedItems = 0;

        foreach ($data as $item) {
            $label = $item['label'];
            $children = $item['children'];

            // detect new items and insert them
            $currentItem = $currentData
                ->whereStrict('parent_id', $parent)
                ->whereStrict('initialLabel', $label)
                ->first();
            if ($currentItem === null) {
                $currentItem = MenuItem::forceCreate([
                    'label' => $label,
                    'initialLabel' => $label,
                    'parent_id' => $parent,
                ]);

                ++$importedItems;
            }

            // insert children
            if (!empty($children)) {
                $importedItems += $this->import($children, $currentItem->id);
            }
        }

        return $importedItems;
    }
}
