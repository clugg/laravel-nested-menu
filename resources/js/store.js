const axios = require('axios').create({baseURL: '/api/'});
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default {
    state: {
        loading: false,
        error: null,
        items: [],
        itemsById: {},
    },
    fetchData() {
        this.state.loading = true;
        this.state.error = null;

        this.setupRootItem();
        axios.get('menu-item').then((response) => {
            const data = response.data;

            // map all of our items into arrays keyed by parent ID
            data.forEach((v) => {
                this.addItemState(v);
                this.state.itemsById[v.id] = v;
                this.state.items.push(v);
            });
        }).catch((e) => {
            this.state.error = e.message || 'Failed to load data from the API.';
        }).finally(() => {
            this.state.loading = false;
        });
    },
    editItem(id, label) {
        const item = this.state.itemsById[id];
        item.isSaving = true;

        axios.patch(`menu-item/${id}`, {label}).then((response) => {
            if (response.data.success) {
                item.label = label;
                item.isSaving = false;
                item.enableEditing = false;
            }
        }).catch((err) => {
            item.isSaving = false;
        });
    },
    addItem(parent_id, label) {
        const parentItem = this.state.itemsById[parent_id];
        parentItem.isAdding = true;

        axios.post(`menu-item`, {parent_id, label}).then((response) => {
            if (response.data) {
                const item = response.data;
                this.addItemState(item);
                this.state.itemsById[item.id] = item;
                this.state.items.push(item);

                parentItem.isAdding = false;
                parentItem.enableAdding = false;
            }
        }).catch((err) => {
            parentItem.isAdding = false;
        });
    },
    deleteItem(id) {
        const item = this.state.itemsById[id];
        item.isDeleting = true;

        axios.delete(`menu-item/${id}`).then((response) => {
            if (response.data.success) {
                this.cleanupItem(id);
            }
        }).catch((err) => {
            item.isDeleting = false;
        });
    },
    cleanupItem(id) {
        const item = this.state.itemsById[id];

        // clean up the item's orphaned children
        this.state.items
            .filter((child) => child.parent_id === id)
            .forEach((child) => {
                this.cleanupItem(child.id);
            });

        // clean up the item
        this.state.items.splice(this.state.items.indexOf(item), 1);
        delete this.state.itemsById[id];
    },
    setupRootItem() {
        // used to manage UI state for adding to the root level
        const rootItem = { id: null };
        this.addItemState(rootItem);
        this.state.items.push(rootItem);
        this.state.itemsById[null] = rootItem;
    },
    addItemState(item) {
        item.enableEditing = false;
        item.enableAdding = false;
        item.isSaving = false;
        item.isAdding = false;
        item.isDeleting = false;
    },
}
