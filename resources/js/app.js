import store from './store';
import Vue from 'vue';

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    data() {
        return {
            store,
        };
    },
    computed: {
        state() {
            if (this.store.state.loading) {
                return 'loading';
            } else if (this.store.state.error !== null) {
                return 'error';
            }

            return 'loaded';
        },
    },
    created() {
        this.store.fetchData();
    },
});
