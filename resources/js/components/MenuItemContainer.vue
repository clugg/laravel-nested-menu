<template>
    <ul>
        <menu-item
            v-for="item in items"
            :key="item.id"
            :item="item"
            :store="store"
            :class="{'is-deleting': item.isDeleting}"
        />

        <li>
            <form
                v-show="parentItem.enableAdding"
                @submit.prevent="store.addItem(parent_id, addValue)"
                :key="`${parent_id}-add`"
            >
                <input
                    type="text"
                    v-model="addValue"
                    :disabled="parentItem.isAdding"
                    ref="addInput"
                />
                <input type="submit" value="Add" :disabled="parentItem.isAdding" />
                <button
                    @click.prevent="setAdding(false)"
                    :disabled="parentItem.isAdding"
                >Cancel</button>
            </form>
            <p v-show="!parentItem.enableAdding">
                <a href="#" @click.prevent="setAdding(true)">[add]</a>
            </p>
        </li>
    </ul>
</template>

<style lang="scss">
.is-deleting {
    color: #606060;
    text-decoration: line-through;

    a {
        pointer-events: none;
        color: #494949;
    }
}

a {
    text-decoration: none;
    font-size: 0.75em;
}
</style>

<script>
    export default {
        props: {
            parent_id: {
                type: Number,
                required: false,
            },
            store: Object,
        },
        computed: {
            items() {
                return this.store.state.items.filter((item) => {
                    return this.parent_id === item.parent_id;
                });
            },
            parentItem() {
                return this.store.state.itemsById[this.parent_id];
            },
        },
        data() {
            return {
                addValue: '',
            };
        },
        methods: {
            setAdding(enabled) {
                this.addValue = '';
                this.parentItem.enableAdding = enabled;
                if (enabled) {
                    this.$nextTick(() => {
                        this.$refs.addInput.focus();
                    });
                }
            },
        },
    }
</script>
