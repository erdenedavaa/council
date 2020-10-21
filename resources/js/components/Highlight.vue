<template>
    <div>
        <div v-html="content" ref="content"></div>
    </div>
</template>

<script>
import Highlighter from 'highlight.js';
import 'highlight.js/styles/foundation.css';

// let Highlighter = require('highlight.js');
// deer import hiisen tul hereggui bolj bn
// require('highlight.js/styles/foundation.css'); // load Foundation style

export default {
    name: "Highlight",

    props: ['content'],

    mounted() {
        this.highlight(this.$refs.content);
    },

    methods: {
        highlight(block) {
            if(! block) return;

            block.querySelectorAll('pre').forEach(
                node => Highlighter.highlightBlock(node)
            );
        }
    },

    watch: {
        // content change hiihiig wachlaj bn
        content() {
            this.$nextTick(() => {
                this.highlight(this.$refs['content']);
            });
        }
    },

}
</script>

<style scoped>

</style>
