<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl">
            <a class="page-link" href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>

        <li class="page-item" v-show="nextUrl">
            <a class="page-link" href="#" aria-label="Next" rel="next" @click.prevent="page++">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    name: "Paginator",

    props: ['dataSet'],

    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false
        }
    },

    watch: {
        dataSet() {
            // paginator lessons for parent dataset and
            // update it's own values
            this.page = this.dataSet.current_page;
            this.prevUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },

        page() {
            this.broadcast().updateUrl();
            // This is signal for means, it wants new data
        }
    },

    computed: {
        shouldPaginate() {
            return !! this.prevUrl || !! this.nextUrl;
        }
    },

    methods: {
        broadcast() {
            return this.$emit('changed', this.page);
            // updated gesen signaliig $page tei hamt deeshee yawuulj bn
        },

        updateUrl() {
            history.pushState(null, null, '?page=' + this.page);
        }

    }
}
</script>

<style scoped>

</style>
