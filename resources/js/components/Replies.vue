<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>
        <!-- dataset is updated and cascade down to paginator -->

        <p v-if="$parent.locked">
            This thread has been locked. No more replies are allowed.
        </p>
        <new-reply @created="add" class="mt-5" v-else></new-reply>
<!--        <new-reply @created="add" v-if="! $parent.locked"></new-reply>-->
        <!--  parent нь not locked үед харагдана. display the form to create new reply       -->
    </div>
</template>

<script>
import Reply from './Reply.vue';
import NewReply from './NewReply.vue';
import collection from '../mixins/Collection';

export default {
    // name: "Replies",

    components: { Reply, NewReply },

    mixins: [collection],

    data() {
        return { dataSet: false }
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch(page) {
            // dooroos $emit eer page irsen tul parametr ni bolj bn
            axios.get(this.url(page)).then(this.refresh);
        },

        url(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);

                page = query ? query[1] : 1;
            }

            return `${location.pathname}/replies?page=${page}`;
        },

        refresh({data}) {
            // Enii ner ni distracturing gedgiin bn
            this.dataSet = data;
            this.items = data.data;

            window.scrollTo(0, 0);
        }
    }
}
</script>

<style scoped>

</style>
