<template>
    <li>
        <form
            v-show="item.enableEditing"
            @submit.prevent="store.editItem(item.id, editValue)"
            :key="`${item.id}-edit`"
        >
            <input
                type="text"
                v-model="editValue"
                :disabled="item.isSaving"
                ref="editInput"
            />
            <input type="submit" value="Save" :disabled="item.isSaving" />
            <button
                @click.prevent="setEditing(false)"
                :disabled="item.isSaving"
            >Cancel</button>
        </form>
        <p v-show="!item.enableEditing">
            {{ item.label }}
            <a href="#" @click.prevent="setEditing(true)">[edit]</a>
            <a href="#" @click.prevent="store.deleteItem(item.id)">[delete]</a>
        </p>

        <menu-item-container
            :parent_id="item.id"
            :store="store"
        />
    </li>
</template>

<script>
    export default {
        props: {
            item: Object,
            store: Object,
        },
        data: function() {
            return {
                editValue: '',
            };
        },
        methods: {
            setEditing(enabled) {
                this.editValue = this.item.label;
                this.item.enableEditing = enabled;
                if (enabled) {
                    this.$nextTick(() => {
                        this.$refs.editInput.focus();
                    });
                }
            },
        }
    }
</script>
